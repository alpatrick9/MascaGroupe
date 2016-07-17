<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/31/16
 * Time: 1:29 PM
 */

namespace Masca\EtudiantBundle\Controller\AdminUniversite;


use Doctrine\DBAL\Exception\ConstraintViolationException;
use Masca\EtudiantBundle\Entity\MatiereParUeFiliere;
use Masca\EtudiantBundle\Entity\Ue;
use Masca\EtudiantBundle\Entity\UeParFiliere;
use Masca\EtudiantBundle\Type\MatiereParUeFiliereType;
use Masca\EtudiantBundle\Type\UeParFiliereType;
use Masca\EtudiantBundle\Type\UeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AdminUeController
 * @package Masca\EtudiantBundle\Controller\AdminUniversite
 * @Route("/universite/admin/ue")
 */
class AdminUeController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="ue_universite")
     */
    public function indexAction(Request $request) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        /**
         * @var $ues Ue[]
         */
        $ues = $this->getDoctrine()->getManager()->getRepository('MascaEtudiantBundle:Ue')->findAll();
        return $this->render('MascaEtudiantBundle:Admin_universite:ue.html.twig',[
            'ues'=>$ues
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/creer/", name="creer_ue_universite")
     */
    public function creerUeAction(Request $request) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $ue = new Ue();
        $form = $this->createForm(UeType::class, $ue);

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($ue);
                $em->flush();
            } catch (ConstraintViolationException $e) {
                return $this->render('MascaEtudiantBundle:Admin_universite:formulaire-ue.html.twig',[
                    'form'=>$form->createView(),
                    'error_message'=>'Le ue '.$ue->getIntitule().' existe déjà'
                ]);
            }

            return $this->redirect($this->generateUrl('ue_universite'));
        }
        return $this->render('MascaEtudiantBundle:Admin_universite:formulaire-ue.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param Ue $ue
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/modifier/{id}", name="modifier_ue_universite")
     */
    public function modifierUeAction(Request $request, Ue $ue) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $form = $this->createForm(UeType::class, $ue);

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            try {
                $this->getDoctrine()->getManager()->flush();
            } catch (ConstraintViolationException $e) {
                return $this->render('MascaEtudiantBundle:Admin_universite:formulaire-ue.html.twig',[
                    'form'=>$form->createView(),
                    'error_message'=>'Le ue '.$ue->getIntitule().' existe déjà'
                ]);
            }
            return $this->redirect($this->generateUrl('ue_universite'));
        }
        return $this->render('MascaEtudiantBundle:Admin_universite:formulaire-ue.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/repartition/{page}",name="repartition_unite_enseignement_univeriste", defaults={"page" = 1})
     */
    public function repartionUeParFiliereAction($page) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $nbParPage = 30;
        /**
         * @var $repartions UeParFiliere[]
         */
        $repartions = $this->getDoctrine()->getManager()->getRepository('MascaEtudiantBundle:UeParFiliere')->getRepartions($nbParPage,$page);

        /**
         * @var $matieres MatiereParUeFiliere[]
         */
        $matieres = [];
        foreach ($repartions as $repartion) {
            $matieres[$repartion->getId()] = $this->getDoctrine()->getManager()->getRepository('MascaEtudiantBundle:MatiereParUeFiliere')->findByUeParFiliere($repartion);
        }
        return $this->render('MascaEtudiantBundle:Admin_universite:ue-par-filiere.html.twig', [
            'repartitions'=>$repartions,
            'matieres'=>$matieres,
            'page'=> $page,
            'nbPage' => ceil(count($repartions)/$nbParPage)
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/repartion/creer/", name="creer_repartition_unite_enseignement_universite")
     */
    public function creationRepartitionUeAction(Request $request) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $repartion = new UeParFiliere();
        $form = $this->createForm(UeParFiliereType::class,$repartion);

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($repartion);
                $em->flush();
            } catch (ConstraintViolationException $e) {
                return $this->render('MascaEtudiantBundle:Admin_universite:formulaire-repartion-ue.html.twig',[
                    'form'=>$form->createView(),
                    'error_message'=>'Les informations avec cette repartition existe déjà'
                ]);
            }
            return $this->redirect($this->generateUrl('repartition_unite_enseignement_univeriste'));
        }
        return $this->render('MascaEtudiantBundle:Admin_universite:formulaire-repartion-ue.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param UeParFiliere $ueParFiliere
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/repartion/matiere/{id}", name="ajouter_m_repartition_unite_enseignement_universite")
     */
    public function ajouterMatiereUeAction(Request $request, UeParFiliere $ueParFiliere) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $matiere = new MatiereParUeFiliere();
        $form = $this->createForm(MatiereParUeFiliereType::class,$matiere);
        if($request->getMethod() == "POST") {
            $form->handleRequest($request);
            $matiere->setUeParFiliere($ueParFiliere);
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($matiere);
                $em->flush();
            } catch (ConstraintViolationException $e) {
                return $this->render('MascaEtudiantBundle:Admin_universite:formulaire-matiere-par-ue.html.twig',[
                    'form'=>$form->createView(),
                    'error_message'=>'Les informations avec cette repartition existe déjà'
                ]);
            }
            return $this->redirect($this->generateUrl('repartition_unite_enseignement_univeriste'));
        }
        return $this->render('MascaEtudiantBundle:Admin_universite:formulaire-matiere-par-ue.html.twig',[
            'form'=>$form->createView()
        ]);
        
    }

    /**
     * @param Request $request
     * @param MatiereParUeFiliere $matiereParUeFiliere
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/repartion/matiere/supprimer/{id}", name="suppr_m_repartition_unite_enseignement_universite")
     * 
     */
    public function supprimerMatiereUeAction(Request $request, MatiereParUeFiliere $matiereParUeFiliere) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($matiereParUeFiliere);
        $em->flush();
        return $this->redirect($this->generateUrl('repartition_unite_enseignement_univeriste'));
    }

}