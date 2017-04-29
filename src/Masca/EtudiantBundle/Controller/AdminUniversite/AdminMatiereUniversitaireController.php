<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/20/16
 * Time: 2:00 PM
 */

namespace Masca\EtudiantBundle\Controller\AdminUniversite;


use Doctrine\DBAL\Exception\ConstraintViolationException;
use Masca\EtudiantBundle\Entity\Matiere;
use Masca\EtudiantBundle\Entity\MatiereRepository;
use Masca\EtudiantBundle\Type\MatiereUniversitaireType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AdminMatiereUniversitaireController
 * @package Masca\EtudiantBundle\Controller\AdminUniversite
 * @Route("/universite/admin/matiere")
 */
class AdminMatiereUniversitaireController extends Controller
{
    /**
     * @param Request $request
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/list/{page}", name="list_matieres_universite", defaults={"page" = 1})
     */
    public function listMatierAction(Request $request,$page) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_ECO_U')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $nbParPage = 30;
        /**
         * @var $repository MatiereRepository
         */
        $repository = $this->getDoctrine()->getManager()
            ->getRepository('MascaEtudiantBundle:Matiere');
        /**
         * @var $matieres Matiere[]
         */
        $matieres = $repository->getMatieres($nbParPage,$page);

        return $this->render('MascaEtudiantBundle:Admin_universite:list-matieres.html.twig',array(
            'matieres'=>$matieres,
            'page'=> $page,
            'nbPage' => ceil(count($matieres)/$nbParPage)));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/ajouter/", name="ajouter_matiere_universite")
     */
    public function ajouterMatiereAction(Request $request) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $matiere = new Matiere();
        $form = $this->createForm(MatiereUniversitaireType::class, $matiere);

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($matiere);
                $em->flush();
            } catch (ConstraintViolationException $e) {
                return $this->render('MascaEtudiantBundle:Admin_universite:formulaire-matieres.html.twig',[
                    'form'=>$form->createView(),
                    'error_message'=>'la matiere '.$matiere->getIntitule().' existe déjà'
                ]);
            }
            return $this->redirect($this->generateUrl('list_matieres_universite'));
        }
        return $this->render('MascaEtudiantBundle:Admin_universite:formulaire-matieres.html.twig',[
            'form'=>$form->createView()
        ]);
    }
    
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/modifier/{matiere_id}", name="modifier_matiere_universite")
     * @ParamConverter("matiere", options={"mapping": {"matiere_id":"id"}})
     */
    public function modifierMatiereAction(Request $request, Matiere $matiere) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $form = $this->createForm(MatiereUniversitaireType::class, $matiere);

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
             try {
                 $this->getDoctrine()->getManager()->flush();
             } catch (ConstraintViolationException $e) {
                 return $this->render('MascaEtudiantBundle:Admin_universite:formulaire-matieres.html.twig',[
                     'form'=>$form->createView(),
                     'error_message'=>'la matiere '.$matiere->getIntitule().' existe déjà'
                 ]);
             }
            return $this->redirect($this->generateUrl('list_matieres_universite'));
        }

        return $this->render('MascaEtudiantBundle:Admin_universite:formulaire-matieres.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param Matiere $matiere
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/supprimer/{id}", name="supprimer_matiere_universite")
     */
    public function supprimerMatiereAction(Request $request, Matiere $matiere) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($matiere);
        $em->flush();
        return $this->redirect($this->generateUrl('list_matieres_universite'));
    }
}