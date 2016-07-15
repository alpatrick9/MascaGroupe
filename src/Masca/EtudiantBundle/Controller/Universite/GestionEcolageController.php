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
use Masca\EtudiantBundle\Type\EcolageUnivType;
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
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_SECRETAIRE')){
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
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_SECRETAIRE')){
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

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            try {
                $datePayement = new DatePayementEcolageUniv();
                $datePayement->setFraisScolariteUniv($ecolage);
                $datePayement->setMontant($ecolage->getMontant());

                if($ecolage->getMontant() + $universitaireSonFiliere->getUniversitaire()->getInfoEtudiant()->getReduction() == $motantEcolage->getMontant()) {
                    $ecolage->setStatus(true);
                }
                $em = $this->getDoctrine()->getManager();
                $em->persist($ecolage);
                $em->persist($datePayement);
                $em->flush();
            } catch (ConstraintViolationException $e) {
                return $this->render('MascaEtudiantBundle:Universite:formularie-ecolage.html.twig', array(
                    'form'=>$form->createView(),
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
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_SECRETAIRE')){
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

        if($request->getMethod() == 'POST') {
            $oldMontant = $fraisScolariteUniv->getMontant();
            $form->handleRequest($request);
            try {
                $datePayement = new DatePayementEcolageUniv();
                $datePayement->setFraisScolariteUniv($fraisScolariteUniv);
                $datePayement->setMontant($fraisScolariteUniv->getMontant());

                $fraisScolariteUniv->setMontant($oldMontant + $fraisScolariteUniv->getMontant());

                if($fraisScolariteUniv->getMontant() + $fraisScolariteUniv->getUnivSonFiliere()->getUniversitaire()->getInfoEtudiant()->getReduction() == $motantEcolage->getMontant()) {
                    $fraisScolariteUniv->setStatus(true);
                }
                $em = $this->getDoctrine()->getManager();
                $em->persist($datePayement);
                $em->flush();
            } catch (ConstraintViolationException $e) {
                return $this->render('MascaEtudiantBundle:Universite:formularie-ecolage.html.twig', array(
                    'form'=>$form->createView(),
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
            'sonFiliere'=>$fraisScolariteUniv->getUnivSonFiliere(),
            'montant'=>$motantEcolage->getMontant(),
            'reduction'=>$fraisScolariteUniv->getUnivSonFiliere()->getUniversitaire()->getInfoEtudiant()->getReduction(),
            'reste'=>$motantEcolage->getMontant()-$fraisScolariteUniv->getMontant()-$fraisScolariteUniv->getUnivSonFiliere()->getUniversitaire()->getInfoEtudiant()->getReduction()
        ));
    }

    /**
     * @param Request $request
     * @param UniversitaireSonFiliere $universitaireSonFiliere
     * @return Response
     * @Route("/print/ecolage/{id}", name="print_ecolage_universite")
     */
    public function printEcolageUnivAction(Request $request,UniversitaireSonFiliere $universitaireSonFiliere) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_ECONOMAT')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }

        $html = $this->forward('MascaEtudiantBundle:Universite/GestionEcolage:index',[
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