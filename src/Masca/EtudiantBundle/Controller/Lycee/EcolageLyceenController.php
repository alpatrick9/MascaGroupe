<?php
/**
 * Created by PhpStorm.
 * User: patmi
 * Date: 16/05/2016
 * Time: 20:40
 */

namespace Masca\EtudiantBundle\Controller\Lycee;


use Doctrine\DBAL\Exception\ConstraintViolationException;
use Masca\EtudiantBundle\Entity\DatePayementEcolageLycee;
use Masca\EtudiantBundle\Entity\FraisScolariteLyceen;
use Masca\EtudiantBundle\Entity\GrilleFraisScolariteLycee;
use Masca\EtudiantBundle\Entity\Lyceen;
use Masca\EtudiantBundle\Type\EcolageLyceenType;
use Masca\TresorBundle\Entity\MvmtLycee;
use Masca\TresorBundle\Entity\SoldeLycee;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RequestContext;

/**
 * Class EcolageLyceenController
 * @package Masca\EtudiantBundle\Controller\Lycee
 * @Route("/lycee/ecolage")
 */
class EcolageLyceenController extends Controller
{
    /**
     * @param Request $request
     * @param Lyceen $lyceen
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/accueil/{id}", name="ecolage_lyceen")
     */
    public function ecolageAction(Request $request,Lyceen $lyceen) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_ECONOMAT')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }

        /**
         * @var $motantEcolage GrilleFraisScolariteLycee
         */
        $motantEcolage = $this->getDoctrine()->getManager()
            ->getRepository('MascaEtudiantBundle:GrilleFraisScolariteLycee')->findOneByClasse($lyceen->getSonClasse());

        if(empty($motantEcolage)) {
            return $this->render("::message-layout.html.twig",[
               'message'=>'Veuillez contacter le responsabe car il n\'y a pas un montant d\'écolage attribué à ce classe: '.$lyceen->getSonClasse()->getIntitule(),
                'previousLink'=>$request->headers->get('referer')
            ]);
        }

        /**
         * @var $statusEcolages FraisScolariteLyceen[]
         */
        $statusEcolages = $this->getDoctrine()->getManager()
            ->getRepository('MascaEtudiantBundle:FraisScolariteLyceen')
            ->findBy(
                array(
                    'lyceen'=>$lyceen,
                    'anneeScolaire'=>$lyceen->getAnneeScolaire()
                ));

        /**
         * @var $datePayements DatePayementEcolageLycee[]
         */
        $datePayements = [];

        foreach ($statusEcolages as $ecolage) {
            $datePayements[$ecolage->getId()] = $this->getDoctrine()->getManager()
                ->getRepository('MascaEtudiantBundle:DatePayementEcolageLycee')->findByFraisScolariteLyceen($ecolage);
        }

        return $this->render('MascaEtudiantBundle:Lycee:ecolage.html.twig', array(
            'lyceen'=>$lyceen,
            'statusEcolages'=> $statusEcolages,
            'montant'=>$motantEcolage->getMontant(),
            'datesPayement'=>$datePayements
        ));
    }

    /**
     * @param Request $request
     * @param Lyceen $lyceen
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/payement/{id}", name="payer_ecolage_lyceen")
     */
    public function payerEcolageAction(Request $request, Lyceen $lyceen) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_ECONOMAT')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $anneeData = explode('-',$lyceen->getAnneeScolaire());
        $choicesAnnee = [];
        foreach ($anneeData as $annee) {
            $choicesAnnee[$annee] = $annee;
        }


        /**
         * @var $motantEcolage GrilleFraisScolariteLycee
         */
        $motantEcolage = $this->getDoctrine()->getManager()
            ->getRepository('MascaEtudiantBundle:GrilleFraisScolariteLycee')->findOneByClasse($lyceen->getSonClasse());

        $sommeDue = $motantEcolage->getMontant() - $lyceen->getInfoEtudiant()->getReduction();

        $fraisScolariteLyceen = new FraisScolariteLyceen();
        $fraisScolariteLyceen->setAnneeScolaire($lyceen->getAnneeScolaire());
        $fraisScolariteLyceen->setLyceen($lyceen);

        $ecolageFrom = $this->createForm(EcolageLyceenType::class,$fraisScolariteLyceen,[
            'trait_choices'=>[
                'mois'=>$this->getParameter('mois'),
                'annees'=>$choicesAnnee,
                'max'=>$sommeDue
            ]
        ]);

        if($request->getMethod() == 'POST') {
            $ecolageFrom->handleRequest($request);

            try {
                $datePayement = new DatePayementEcolageLycee();
                $datePayement->setFraisScolariteLyceen($fraisScolariteLyceen);
                $datePayement->setMontant($fraisScolariteLyceen->getMontant());

                if($fraisScolariteLyceen->getMontant() + $lyceen->getInfoEtudiant()->getReduction() == $motantEcolage->getMontant()) {
                    $fraisScolariteLyceen->setStatus(true);
                }
                $em = $this->getDoctrine()->getManager();
                $em->persist($fraisScolariteLyceen);
                $em->persist($datePayement);

                $mvmt = new MvmtLycee();
                $mvmt->setSomme($fraisScolariteLyceen->getMontant());
                $mvmt->setDescription('Ecolage de l\'élève matricule '.$lyceen->getPerson()->getNumMatricule().' mois de '.$fraisScolariteLyceen->getMois());
                /**
                 * @var $solde SoldeLycee
                 */
                $solde = $this->getDoctrine()->getRepository('MascaTresorBundle:SoldeLycee')->find(1);

                if(empty($solde)) {
                    $solde = new SoldeLycee();
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($solde);
                }

                $solde->setDate($mvmt->getDate());

                $mvmt->setSoldePrecedent($solde->getSolde());
                $mvmt->setTypeOperation('c');
                $solde->setSolde($solde->getSolde() + $mvmt->getSomme());
                $mvmt->setSoldeApres($solde->getSolde());
                $em->persist($mvmt);

                $em->flush();
            } catch (ConstraintViolationException $e) {
                return $this->render('MascaEtudiantBundle:Lycee:payementecolage.html.twig', array(
                    'ecolageForm'=>$ecolageFrom->createView(),
                    'lyceen'=>$lyceen,
                    'montant'=>$motantEcolage->getMontant(),
                    'reduction'=>$lyceen->getInfoEtudiant()->getReduction(),
                    'error_message'=>'Vous avez déjà un enregistrement d\'ecolage pour le mois '.$fraisScolariteLyceen->getMois().' de l\'année '.$fraisScolariteLyceen->getAnnee()
                ));
            }
            return $this->redirect($this->generateUrl('ecolage_lyceen', array('id'=>$lyceen->getId())));
        }
        return $this->render('MascaEtudiantBundle:Lycee:payementecolage.html.twig', array(
            'ecolageForm'=>$ecolageFrom->createView(),
            'lyceen'=>$lyceen,
            'montant'=>$motantEcolage->getMontant(),
            'reduction'=>$lyceen->getInfoEtudiant()->getReduction()
        ));
    }

    /**
     * @param Request $request
     * @param FraisScolariteLyceen $fraisScolariteLyceen
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @ParamConverter("fraisScolariteLyceen", options={"mapping": {"frais_scolarite_id":"id"}})
     * @Route("/regularisation-reste/{frais_scolarite_id}", name="regularisation_reste_ecolage_lyceen")
     */
    public function regularisationResteEcolageAction(Request $request, FraisScolariteLyceen $fraisScolariteLyceen) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_ECONOMAT')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $anneeData = explode('-',$fraisScolariteLyceen->getLyceen()->getAnneeScolaire());
        $choicesAnnee = array($anneeData[0]=>$anneeData[0],$anneeData[1]=>$anneeData[1]);

        /**
         * @var $motantEcolage GrilleFraisScolariteLycee
         */
        $motantEcolage = $this->getDoctrine()->getManager()
            ->getRepository('MascaEtudiantBundle:GrilleFraisScolariteLycee')->findOneByClasse($fraisScolariteLyceen->getLyceen()->getSonClasse());


        $ecolageFrom = $this->createForm(EcolageLyceenType::class,$fraisScolariteLyceen,[
            'trait_choices'=>[
                'mois'=>$this->getParameter('mois'),
                'annees'=>$choicesAnnee,
                'max'=>$motantEcolage->getMontant() - $fraisScolariteLyceen->getMontant() -$fraisScolariteLyceen->getLyceen()->getInfoEtudiant()->getReduction()
                ]
        ]);

        $moisField = $ecolageFrom->get('mois');
        $options = $moisField->getConfig()->getOptions();
        $options['disabled'] = true;
        $ecolageFrom->add('mois',$moisField->getConfig()->getType()->getInnerType(),$options);

        $anneeField = $ecolageFrom->get('annee');
        $options = $anneeField->getConfig()->getOptions();
        $options['disabled'] = true;
        $ecolageFrom->add('annee',$anneeField->getConfig()->getType()->getInnerType(),$options);

        $montatField = $ecolageFrom->get('montant');
        $options = $montatField->getConfig()->getOptions();
        $options['data'] = null;
        $ecolageFrom->add('montant',$montatField->getConfig()->getType()->getInnerType(),$options);

        if($request->getMethod() == 'POST') {
            $oldMontant = $fraisScolariteLyceen->getMontant();
            $ecolageFrom->handleRequest($request);

            $datePayement = new DatePayementEcolageLycee();
            $datePayement->setFraisScolariteLyceen($fraisScolariteLyceen);
            $datePayement->setMontant($fraisScolariteLyceen->getMontant());

            $datePayement = new DatePayementEcolageLycee();
            $datePayement->setFraisScolariteLyceen($fraisScolariteLyceen);
            $datePayement->setMontant($fraisScolariteLyceen->getMontant());

            $mvmt = new MvmtLycee();
            $mvmt->setSomme($fraisScolariteLyceen->getMontant());

            $fraisScolariteLyceen->setMontant($oldMontant + $fraisScolariteLyceen->getMontant());

            if($fraisScolariteLyceen->getMontant() + $fraisScolariteLyceen->getLyceen()->getInfoEtudiant()->getReduction() == $motantEcolage->getMontant())
                $fraisScolariteLyceen->setStatus(true);

            $em = $this->getDoctrine()->getManager();
            $em->persist($datePayement);


            $mvmt->setDescription('Ecolage de l\'élève matricule '.$fraisScolariteLyceen->getLyceen()->getPerson()->getNumMatricule().' mois de '.$fraisScolariteLyceen->getMois());
            /**
             * @var $solde SoldeLycee
             */
            $solde = $this->getDoctrine()->getRepository('MascaTresorBundle:SoldeLycee')->find(1);
            $solde->setDate($mvmt->getDate());

            $mvmt->setSoldePrecedent($solde->getSolde());
            $mvmt->setTypeOperation('c');
            $solde->setSolde($solde->getSolde() + $mvmt->getSomme());
            $mvmt->setSoldeApres($solde->getSolde());
            $em->persist($mvmt);
            $em->flush();

            return $this->redirect($this->generateUrl('ecolage_lyceen',array('id'=>$fraisScolariteLyceen->getLyceen()->getId())));
        }
        return $this->render('MascaEtudiantBundle:Lycee:regularisation-reste-ecolage.html.twig', array(
            'form'=>$ecolageFrom->createView(),
            'renseignement'=>$fraisScolariteLyceen,
            'reste'=>$motantEcolage->getMontant()-$fraisScolariteLyceen->getMontant()-$fraisScolariteLyceen->getLyceen()->getInfoEtudiant()->getReduction()
        ));
    }

    /**
     * @param Request $request
     * @param FraisScolariteLyceen $fraisScolariteLyceen
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/delete/{id}", name="supprimer_ecolage_lyceen")
     */
    public function deleteEcolageAction(Request $request, FraisScolariteLyceen $fraisScolariteLyceen) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_ECONOMAT')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($fraisScolariteLyceen);
        $em->flush();
        return $this->redirect($this->generateUrl('ecolage_lyceen',array('id'=>$fraisScolariteLyceen->getLyceen()->getId())));
    }

    /**
     * @param Request $request
     * @param Lyceen $lyceen
     * @return Response
     * @Route("/print/{id}", name="print_ecolage")
     */
    public function printEcolageAction(Request $request,Lyceen $lyceen) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_ECONOMAT')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }

        $html = $this->forward('MascaEtudiantBundle:Lycee/Impression/LyceeImpression:ecolageLyceePrint',[
            'lyceen'=>$lyceen
        ])->getContent();

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => sprintf('inline; filename="%s"', 'out.pdf'),
            ]
        );
    }

}