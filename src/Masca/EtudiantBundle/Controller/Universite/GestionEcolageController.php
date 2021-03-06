<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/26/16
 * Time: 3:27 PM
 */

namespace Masca\EtudiantBundle\Controller\Universite;


use Doctrine\DBAL\Exception\ConstraintViolationException;
use Masca\EtudiantBundle\Entity\DatePayementEcolageUniv;
use Masca\EtudiantBundle\Entity\FraisScolariteUniv;
use Masca\EtudiantBundle\Entity\GrilleFraisScolariteUniversite;
use Masca\EtudiantBundle\Entity\UniversitaireSonFiliere;
use Masca\EtudiantBundle\Type\DatePayementEcoLyceeType;
use Masca\EtudiantBundle\Type\DatePayementEcoUnivType;
use Masca\EtudiantBundle\Type\EcolageUnivType;
use Masca\EtudiantBundle\Type\SonFiliereType;
use Masca\TresorBundle\Entity\MvmtUniversite;
use Masca\TresorBundle\Entity\SoldeUniversite;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class GestionEcolageController
 * @package Masca\EtudiantBundle\Controller\Universite
 * @Route("/universite/ecolage")
 */
class GestionEcolageController extends Controller
{
    /**
     * @param Request $request
     * @param UniversitaireSonFiliere $universitaireSonFiliere
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/{id}", name="ecolage_universitaire")
     */
    public function indexAction(Request $request, UniversitaireSonFiliere $universitaireSonFiliere) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_ECO_U')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }

        /**
         * @var $motantEcolage GrilleFraisScolariteUniversite
         */
        $motantEcolage = $this->getDoctrine()->getManager()
            ->getRepository('MascaEtudiantBundle:GrilleFraisScolariteUniversite')->findOneBy([
                'filiere'=>$universitaireSonFiliere->getSonFiliere(),
                'niveauEtude'=>$universitaireSonFiliere->getSonNiveauEtude()
            ]);

        if(empty($motantEcolage)) {
            return $this->render("::message-layout.html.twig",[
                'message'=>'Veuillez contacter le responsabe car il n\'y a pas un montant d\'écolage attribué au filière: '.
                    $universitaireSonFiliere->getSonFiliere()->getIntitule().' niveau '.$universitaireSonFiliere->getSonNiveauEtude()->getIntitule(),
                'previousLink'=>$request->headers->get('referer')
            ]);
        }

        /**
         * @var $statusEcolages FraisScolariteUniv[]
         */
        $statusEcolages = $this->getDoctrine()->getManager()->getRepository('MascaEtudiantBundle:FraisScolariteUniv')->findByUnivSonFiliere($universitaireSonFiliere);

        /**
         * @var $datePayements DatePayementEcolageUniv[]
         */
        $datePayements = [];

        foreach ($statusEcolages as $ecolage) {
            $datePayements[$ecolage->getId()] = $this->getDoctrine()->getManager()
                ->getRepository('MascaEtudiantBundle:DatePayementEcolageUniv')->findByFraisScolariteUniv($ecolage);
        }

        return $this->render('MascaEtudiantBundle:Universite:ecolage.html.twig', array(
            'sonFiliere'=>$universitaireSonFiliere,
            'statusEcolages'=> $statusEcolages,
            'montant'=>$motantEcolage->getMontant(),
            'datesPayement'=>$datePayements
        ));
    }

    /**
     * @param Request $request
     * @param UniversitaireSonFiliere $universitaireSonFiliere
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/payement/{id}", name="payer_ecolage_univeristaire")
     */
    public function payerEcolageAction(Request $request, UniversitaireSonFiliere $universitaireSonFiliere) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_ECO_U')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $anneeData = range($universitaireSonFiliere->getAnneeEtude() - 2, $universitaireSonFiliere->getAnneeEtude()+2);
        $choicesAnnee = [];
        foreach ($anneeData as $annee) {
            $choicesAnnee[$annee] = $annee;
        }

        /**
         * @var $motantEcolage GrilleFraisScolariteUniversite
         */
        $motantEcolage = $this->getDoctrine()->getManager()
            ->getRepository('MascaEtudiantBundle:GrilleFraisScolariteUniversite')->findOneBy([
                'filiere'=>$universitaireSonFiliere->getSonFiliere(),
                'niveauEtude'=>$universitaireSonFiliere->getSonNiveauEtude()
            ]);

        $sommeDue = $motantEcolage->getMontant() - $universitaireSonFiliere->getUniversitaire()->getInfoEtudiant()->getReduction();
        $ecolage = new FraisScolariteUniv();
        $ecolage->setUnivSonFiliere($universitaireSonFiliere);

        $form = $this->createForm(EcolageUnivType::class,$ecolage,[
            'trait_choices'=>[
                'mois'=>$this->getParameter('mois'),
                'annees'=>$choicesAnnee,
                'max'=>$sommeDue
            ]
        ]);

        $datePayement = new DatePayementEcolageUniv();
        $dateForm = $this->createForm(DatePayementEcoUnivType::class, $datePayement);
        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $dateForm->handleRequest($request);
            try {
                $datePayement->setFraisScolariteUniv($ecolage);
                $datePayement->setMontant($ecolage->getMontant());

                if($ecolage->getMontant() + $universitaireSonFiliere->getUniversitaire()->getInfoEtudiant()->getReduction() == $motantEcolage->getMontant()) {
                    $ecolage->setStatus(true);
                }
                $em = $this->getDoctrine()->getManager();
                $em->persist($ecolage);
                $em->persist($datePayement);

                $mvmt = new MvmtUniversite();
                $mvmt->setSomme($ecolage->getMontant());
                $mvmt->setDescription('Ecolage de l\'étudiant matricule '.$universitaireSonFiliere->getUniversitaire()->getPerson()->getNumMatricule().' mois de '.$ecolage->getMois());
                /**
                 * @var $solde SoldeUniversite
                 */
                $solde = $this->getDoctrine()->getRepository('MascaTresorBundle:SoldeUniversite')->find(1);
                if(empty($solde)) {
                    $solde = new SoldeUniversite();
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
                return $this->render('MascaEtudiantBundle:Universite:formularie-ecolage.html.twig', array(
                    'form'=>$form->createView(),
                    'dateForm'=>$dateForm->createView(),
                    'sonFiliere'=>$universitaireSonFiliere,
                    'montant'=>$motantEcolage->getMontant(),
                    'reduction'=>$universitaireSonFiliere->getUniversitaire()->getInfoEtudiant()->getReduction(),
                    'error_message'=>'Vous avez déjà un enregistrement d\'ecolage pour le mois '.$ecolage->getMois().' de l\'année '.$ecolage->getAnnee()
                ));
            }
            return $this->redirect($this->generateUrl('ecolage_universitaire', array('id'=>$universitaireSonFiliere->getId())));
        }

        return $this->render('MascaEtudiantBundle:Universite:formularie-ecolage.html.twig', array(
            'form'=>$form->createView(),
            'dateForm'=>$dateForm->createView(),
            'sonFiliere'=>$universitaireSonFiliere,
            'montant'=>$motantEcolage->getMontant(),
            'reduction'=>$universitaireSonFiliere->getUniversitaire()->getInfoEtudiant()->getReduction()
        ));

    }

    /**
     * @param Request $request
     * @param FraisScolariteUniv $fraisScolariteUniv
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/regularisation-reste/{id}", name="regularisation_rest_ecolage_univesitaire")
     */
    public function regularisationResteEcolageAction(Request $request, FraisScolariteUniv $fraisScolariteUniv) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_ECO_U')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $anneeData = range($fraisScolariteUniv->getUnivSonFiliere()->getAnneeEtude() - 2, $fraisScolariteUniv->getUnivSonFiliere()->getAnneeEtude()+2);
        $choicesAnnee = [];
        foreach ($anneeData as $annee) {
            $choicesAnnee[$annee] = $annee;
        }

        /**
         * @var $motantEcolage GrilleFraisScolariteUniversite
         */
        $motantEcolage = $this->getDoctrine()->getManager()
            ->getRepository('MascaEtudiantBundle:GrilleFraisScolariteUniversite')->findOneBy([
                'filiere'=>$fraisScolariteUniv->getUnivSonFiliere()->getSonFiliere(),
                'niveauEtude'=>$fraisScolariteUniv->getUnivSonFiliere()->getSonNiveauEtude()
            ]);

        $sommeDue = $motantEcolage->getMontant() - $fraisScolariteUniv->getMontant() - $fraisScolariteUniv->getUnivSonFiliere()->getUniversitaire()->getInfoEtudiant()->getReduction();

        $form = $this->createForm(EcolageUnivType::class,$fraisScolariteUniv,[
            'trait_choices'=>[
                'mois'=>$this->getParameter('mois'),
                'annees'=>$choicesAnnee,
                'max'=>$sommeDue
            ]
        ]);

        $moisField = $form->get('mois');
        $options = $moisField->getConfig()->getOptions();
        $options['disabled'] = true;
        $form->add('mois',$moisField->getConfig()->getType()->getInnerType(),$options);

        $anneeField = $form->get('annee');
        $options = $anneeField->getConfig()->getOptions();
        $options['disabled'] = true;
        $form->add('annee',$anneeField->getConfig()->getType()->getInnerType(),$options);

        $montatField = $form->get('montant');
        $options = $montatField->getConfig()->getOptions();
        $options['data'] = null;
        $form->add('montant',$montatField->getConfig()->getType()->getInnerType(),$options);

        $datePayement = new DatePayementEcolageUniv();
        $dateForm = $this->createForm(DatePayementEcoUnivType::class, $datePayement);

        if($request->getMethod() == 'POST') {
            $oldMontant = $fraisScolariteUniv->getMontant();
            $form->handleRequest($request);
            $dateForm->handleRequest($request);
            try {
                $datePayement->setFraisScolariteUniv($fraisScolariteUniv);
                $datePayement->setMontant($fraisScolariteUniv->getMontant());
                $mvmt = new MvmtUniversite();
                $mvmt->setSomme($fraisScolariteUniv->getMontant());
                $fraisScolariteUniv->setMontant($oldMontant + $fraisScolariteUniv->getMontant());

                if($fraisScolariteUniv->getMontant() + $fraisScolariteUniv->getUnivSonFiliere()->getUniversitaire()->getInfoEtudiant()->getReduction() == $motantEcolage->getMontant()) {
                    $fraisScolariteUniv->setStatus(true);
                }
                $em = $this->getDoctrine()->getManager();
                $em->persist($datePayement);


                $mvmt->setDescription('Ecolage de l\'étudiant matricule '.$fraisScolariteUniv->getUnivSonFiliere()->getUniversitaire()->getPerson()->getNumMatricule().' mois de '.$fraisScolariteUniv->getMois());
                /**
                 * @var $solde SoldeUniversite
                 */
                $solde = $this->getDoctrine()->getRepository('MascaTresorBundle:SoldeUniversite')->find(1);
                $solde->setDate($mvmt->getDate());

                $mvmt->setSoldePrecedent($solde->getSolde());
                $mvmt->setTypeOperation('c');
                $solde->setSolde($solde->getSolde() + $mvmt->getSomme());
                $mvmt->setSoldeApres($solde->getSolde());
                $em->persist($mvmt);

                $em->flush();
            } catch (ConstraintViolationException $e) {
                return $this->render('MascaEtudiantBundle:Universite:formularie-ecolage.html.twig', array(
                    'form'=>$form->createView(),
                    'dateForm'=>$dateForm->createView(),
                    'sonFiliere'=>$fraisScolariteUniv->getUnivSonFiliere(),
                    'montant'=>$motantEcolage->getMontant(),
                    'reduction'=>$fraisScolariteUniv->getUnivSonFiliere()->getUniversitaire()->getInfoEtudiant()->getReduction(),
                    'error_message'=>'Vous avez déjà un enregistrement d\'ecolage pour le mois '.$fraisScolariteUniv->getMois().' de l\'année '.$fraisScolariteUniv->getAnnee()
                ));
            }
            return $this->redirect($this->generateUrl('ecolage_universitaire', array('id'=>$fraisScolariteUniv->getUnivSonFiliere()->getId())));
        }
        return $this->render('MascaEtudiantBundle:Universite:formularie-ecolage.html.twig', array(
            'form'=>$form->createView(),
            'dateForm'=>$dateForm->createView(),
            'sonFiliere'=>$fraisScolariteUniv->getUnivSonFiliere(),
            'montant'=>$motantEcolage->getMontant(),
            'reduction'=>$fraisScolariteUniv->getUnivSonFiliere()->getUniversitaire()->getInfoEtudiant()->getReduction(),
            'reste'=>$motantEcolage->getMontant()-$fraisScolariteUniv->getMontant()-$fraisScolariteUniv->getUnivSonFiliere()->getUniversitaire()->getInfoEtudiant()->getReduction()
        ));
    }

    /**
     * @param Request $request
     * @param FraisScolariteUniv $fraisScolariteUniv
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/supprimer-ecolage/{id}", name="supprimer_ecolage_univ")
     */
    public function supprimerEcolageUnivAction(Request $request, FraisScolariteUniv $fraisScolariteUniv) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($fraisScolariteUniv);
        $em->flush();
        return $this->redirect($this->generateUrl('ecolage_universitaire', array('id'=>$fraisScolariteUniv->getUnivSonFiliere()->getId())));
    }

    /**
     * @param Request $request
     * @param UniversitaireSonFiliere $universitaireSonFiliere
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/droit/{id}", name="payer_droit_univ")
     */
    public function payerDroitInscriptionAction(Request $request, UniversitaireSonFiliere $universitaireSonFiliere) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_ECO_U')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }

        if($request->getMethod() == "POST") {
            $em = $this->getDoctrine()->getManager();
            $universitaireSonFiliere->setDroitInscription(true);
            $mvmt = new MvmtUniversite();
            $mvmt->setSomme($universitaireSonFiliere->getSonFiliere()->getDroitInscription());
            $mvmt->setDescription('Droit d\'inscription de l\'étudiant matricule '.
                $universitaireSonFiliere->getUniversitaire()->getPerson()->getNumMatricule().' en filière de '.$universitaireSonFiliere->getSonFiliere()->getIntitule().
                ' niveau d\'etude '.$universitaireSonFiliere->getSonNiveauEtude()->getIntitule());
            /**
             * @var $solde SoldeUniversite
             */
            $solde = $this->getDoctrine()->getRepository('MascaTresorBundle:SoldeUniversite')->find(1);

            if(empty($solde)) {
                $solde = new SoldeUniversite();
                $em->persist($solde);
            }

            $solde->setDate($mvmt->getDate());

            $mvmt->setSoldePrecedent($solde->getSolde());
            $mvmt->setTypeOperation('c');
            $solde->setSolde($solde->getSolde() + $mvmt->getSomme());
            $mvmt->setSoldeApres($solde->getSolde());
            $em->persist($mvmt);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('ecolage_universitaire', ['id'=>$universitaireSonFiliere->getId()]));
    }
    
    /**
     * @param Request $request
     * @param UniversitaireSonFiliere $universitaireSonFiliere
     * @return Response
     * @Route("/print/ecolage/{id}", name="print_ecolage_universite")
     */
    public function printEcolageUnivAction(Request $request,UniversitaireSonFiliere $universitaireSonFiliere) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }

        $html = $this->forward('MascaEtudiantBundle:Universite/Impression/UniversiteImpression:ecolagePrint',[
            'universitaireSonFiliere'=>$universitaireSonFiliere
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