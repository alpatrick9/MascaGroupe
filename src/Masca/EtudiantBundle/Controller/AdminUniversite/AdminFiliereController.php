<?php
/**
 * Created by PhpStorm.
 * User: patmi
 * Date: 06/05/2016
 * Time: 10:17
 */

namespace Masca\EtudiantBundle\Controller\AdminUniversite;


use Doctrine\DBAL\Exception\ConstraintViolationException;
use Masca\EtudiantBundle\Entity\Filiere;
use Masca\EtudiantBundle\Entity\FiliereRepository;
use Masca\EtudiantBundle\Type\FiliereType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class AdminFiliereController
 * @package Masca\EtudiantBundle\Controller\AdminUniversite
 * @Route("/universite/admin/filiere")
 */
class AdminFiliereController extends Controller
{
    /**
     * @param Request $request
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/{page}", name="admin_univ_filiere", defaults={"page"=1})
     */
    public function indexAction(Request $request,$page) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_SECRETAIRE')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $nbParPage = 30;
        /**
         * @var $repository FiliereRepository
         */
        $repository = $this->getDoctrine()->getManager()
            ->getRepository('MascaEtudiantBundle:Filiere');
        /**
         * @var $filieres Filiere[]
         */
        $filieres = $repository->getFiliers($nbParPage,$page);

        return $this->render('MascaEtudiantBundle:Admin_universite:index.html.twig',array(
            'filieres'=>$filieres,
            'page'=> $page,
            'nbPage' => ceil(count($filieres)/$nbParPage)));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/ajoute/", name="ajouter_filiere_univ")
     */
    public function ajouterFiliereAction(Request $request) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $filiere = new Filiere();
        $form = $this->createForm(FiliereType::class, $filiere);

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($filiere);
                $em->flush();
            } catch (ConstraintViolationException $e) {
                return $this->render('MascaEtudiantBundle:Admin_universite:formulaire-filiere.html.twig',[
                    'form'=>$form->createView(),
                    'error_message'=>'Le filiere '.$filiere->getIntitule().' existe déjà'
                ]);
            }

            return $this->redirect($this->generateUrl('admin_univ_filiere'));
        }
        return $this->render('MascaEtudiantBundle:Admin_universite:formulaire-filiere.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/modifier/{filiere_id}", name="modifier_filiere_univ")
     * @ParamConverter("filiere", options={"mapping": {"filiere_id":"id"}})
     */
    public function modifierFiliereAction(Request $request, Filiere $filiere) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $form = $this->createForm(FiliereType::class, $filiere);

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            try {
                $this->getDoctrine()->getManager()->flush();
            } catch (ConstraintViolationException $e) {
                return $this->render('MascaEtudiantBundle:Admin_universite:formulaire-filiere.html.twig',[
                    'form'=>$form->createView(),
                    'error_message'=>'Le filiere '.$filiere->getIntitule().' existe déjà'
                ]);
            }
            return $this->redirect($this->generateUrl('admin_univ_filiere'));
        }
        return $this->render('MascaEtudiantBundle:Admin_universite:formulaire-filiere.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param Filiere $fileiere
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/supprimer/{id}", name="supprimer_filiere_univ")
     */
    public function supprimerFiliereAction(Request $request, Filiere $fileiere) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($fileiere);
        $em->flush();
        return $this->redirect($this->generateUrl('admin_univ_filiere'));
    }

}