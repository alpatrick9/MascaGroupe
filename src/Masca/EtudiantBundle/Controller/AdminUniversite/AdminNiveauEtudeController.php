<?php
/**
 * Created by PhpStorm.
 * User: patmi
 * Date: 16/05/2016
 * Time: 20:32
 */

namespace Masca\EtudiantBundle\Controller\AdminUniversite;


use Doctrine\DBAL\Exception\ConstraintViolationException;
use Masca\EtudiantBundle\Entity\NiveauEtude;
use Masca\EtudiantBundle\Type\NiveauEtudeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AdminNiveauEtudeController
 * @package Masca\EtudiantBundle\Controller\AdminUniversite
 * @Route("/universite/admin/niveau-etude")
 */
class AdminNiveauEtudeController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="niveau_etude_univ")
     */
    public function niveauEtudeAction(Request $request) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_ECO_U')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        return $this->render('MascaEtudiantBundle:Admin_universite:niveau-etude.html.twig',[
            'niveauEtudes'=> $this->getDoctrine()->getManager()
                ->getRepository('MascaEtudiantBundle:NiveauEtude')->findAll()
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/ajouter/", name="ajouter_niveau_etude_univ")
     */
    public function ajouterNiveauEtudeAction(Request $request) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $niveau = new NiveauEtude();
        $form = $this->createForm(NiveauEtudeType::class, $niveau);

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($niveau);
                $em->flush();
            } catch (ConstraintViolationException $e) {
                return $this->render('MascaEtudiantBundle:Admin_universite:formulaire-niveau-etude.html.twig',[
                    'form'=>$form->createView(),
                    'error_message'=>'Le niveau '.$niveau->getIntitule().' existe déjà'
                ]);
            }
            return $this->redirect($this->generateUrl('niveau_etude_univ'));
        }
        return $this->render('MascaEtudiantBundle:Admin_universite:formulaire-niveau-etude.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/modifier/{niveau_id}", name="modifier_niveau_etude_univ")
     * @ParamConverter("niveauEtude", options={"mapping": {"niveau_id":"id"}})
     */
    public function modifierNiveauEtudeAction(Request $request, NiveauEtude $niveauEtude) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $form = $this->createForm(NiveauEtudeType::class, $niveauEtude);

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            try {
                $this->getDoctrine()->getManager()->flush();
            } catch (ConstraintViolationException $e) {
                return $this->render('MascaEtudiantBundle:Admin_universite:formulaire-niveau-etude.html.twig',[
                    'form'=>$form->createView(),
                    'error_message'=>'Le niveau '.$niveauEtude->getIntitule().' existe déjà'
                ]);
            }
            return $this->redirect($this->generateUrl('niveau_etude_univ'));
        }
        return $this->render('MascaEtudiantBundle:Admin_universite:formulaire-niveau-etude.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param NiveauEtude $niveauEtude
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/supprimer/{id}", name="supprimer_niveau_univ")
     */
    public function supprimerNiveauAction(Request $request, NiveauEtude $niveauEtude) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($niveauEtude);
        $em->flush();
        return $this->redirect($this->generateUrl('niveau_etude_univ'));
    }
}