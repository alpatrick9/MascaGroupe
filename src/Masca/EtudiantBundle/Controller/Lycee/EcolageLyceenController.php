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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;

class EcolageLyceenController extends Controller
{
    /**
     * @param Request $request
     * @param Lyceen $lyceen
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/lycee/ecolage/accueil/{id}", name="ecolage_lyceen")
     */
    public function ecolageAction(Request $request,Lyceen $lyceen) {
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
     * @Route("/lycee/ecolage/payer/{id}", name="payer_ecolage_lyceen")
     */
    public function payerEcolageAction(Request $request, Lyceen $lyceen) {
        $anneeData = explode('-',$lyceen->getAnneeScolaire());
        $choicesAnnee = array($anneeData[0]=>$anneeData[0],$anneeData[1]=>$anneeData[1]);


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

                if($fraisScolariteLyceen->getMontant() - $lyceen->getInfoEtudiant()->getReduction() == $motantEcolage->getMontant()) {
                    $fraisScolariteLyceen->setStatus(true);
                }
                $em = $this->getDoctrine()->getManager();
                $em->persist($fraisScolariteLyceen);
                $em->persist($datePayement);

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
     * @Route("/lycee/ecolage/regularisation-reste/{frais_scolarite_id}", name="regularisation_reste_ecolage_lyceen")
     */
    public function regularisationResteEcolageAction(Request $request, FraisScolariteLyceen $fraisScolariteLyceen) {
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

            $fraisScolariteLyceen->setMontant($oldMontant + $fraisScolariteLyceen->getMontant());

            if($fraisScolariteLyceen->getMontant() + $fraisScolariteLyceen->getLyceen()->getInfoEtudiant()->getReduction() == $motantEcolage->getMontant())
                $fraisScolariteLyceen->setStatus(true);

            $em = $this->getDoctrine()->getManager();
            $em->persist($datePayement);
            $em->flush();

            return $this->redirect($this->generateUrl('ecolage_lyceen',array('id'=>$fraisScolariteLyceen->getLyceen()->getId())));
        }
        return $this->render('MascaEtudiantBundle:Lycee:regularisation-reste-ecolage.html.twig', array(
            'form'=>$ecolageFrom->createView(),
            'renseignement'=>$fraisScolariteLyceen,
            'reste'=>$motantEcolage->getMontant()-$fraisScolariteLyceen->getMontant()-$fraisScolariteLyceen->getLyceen()->getInfoEtudiant()->getReduction()
        ));
    }

}