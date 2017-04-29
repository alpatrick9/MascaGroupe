<?php
/**
 * Created by PhpStorm.
 * User: patmi
 * Date: 16/05/2016
 * Time: 20:35
 */

namespace Masca\EtudiantBundle\Controller\AdminUniversite;


use Doctrine\DBAL\Exception\ConstraintViolationException;
use Masca\EtudiantBundle\Entity\GrilleFraisScolariteUniversite;
use Masca\EtudiantBundle\Type\GrilleEcolageUniversiteType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AdminEcolageUniversiteController
 * @package Masca\EtudiantBundle\Controller\AdminUniversite
 * @Route("/universite/admin/ecolage")
 */
class AdminEcolageUniversiteController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/grille/", name="grille_ecolage_universite")
     */
    public function grilleFraisScolariteAction(Request $request) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        return $this->render('MascaEtudiantBundle:Admin_universite:grilles-frais-scolarite.html.twig',[
            'grilles'=>$this->getDoctrine()->getManager()->getRepository('MascaEtudiantBundle:GrilleFraisScolariteUniversite')->findAll()
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/ajouter-grille/", name="ajouter_grille_ecolage_universite")
     */
    public function ajouterGrilleAction(Request $request) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $grille = new GrilleFraisScolariteUniversite();
        $form = $this->createForm(GrilleEcolageUniversiteType::class, $grille);
        
        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($grille);
                $em->flush();
            } catch (ConstraintViolationException $e) {
                return $this->render('MascaEtudiantBundle:Admin_universite:formulaire-grilles-frais-scolarite.html.twig',[
                    'form'=>$form->createView(),
                    'error_message'=>'le grille d\'écolage pour '.$grille->getFiliere()->getIntitule().' niveau '.$grille->getNiveauEtude()->getIntitule().' existe déjà'
                ]);
            }
            return $this->redirect($this->generateUrl('grille_ecolage_universite'));
        }
        return $this->render('MascaEtudiantBundle:Admin_universite:formulaire-grilles-frais-scolarite.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/modifier-grille/{grille_id}", name="modifier_grille_ecolage_universite")
     * @ParamConverter("grilleFraisScolariteUniversite", options={"mapping": {"grille_id":"id"}})
     */
    public function modifierGrilleAction(Request $request, GrilleFraisScolariteUniversite $grilleFraisScolariteUniversite) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $form = $this->createForm(GrilleEcolageUniversiteType::class, $grilleFraisScolariteUniversite);

        $options = $form->get('filiere')->getConfig()->getOptions();
        $options['disabled'] = true;
        $form->add('filiere',EntityType::class,$options);

        $options = $form->get('niveauEtude')->getConfig()->getOptions();
        $options['disabled'] = true;
        $form->add('niveauEtude',EntityType::class,$options);

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirect($this->generateUrl('grille_ecolage_universite'));
        }
        return $this->render('MascaEtudiantBundle:Admin_universite:formulaire-grilles-frais-scolarite.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param GrilleFraisScolariteUniversite $grilleFraisScolariteUniversite
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/supprimer/{id}", name="supprimer_grille_univ")
     */
    public function supprimerGrilleEcolageAction(Request $request, GrilleFraisScolariteUniversite $grilleFraisScolariteUniversite) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($grilleFraisScolariteUniversite);
        $em->flush();
        return $this->redirect($this->generateUrl('grille_ecolage_universite'));
    }
}