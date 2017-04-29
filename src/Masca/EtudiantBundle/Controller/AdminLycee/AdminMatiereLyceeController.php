<?php
/**
 * Created by PhpStorm.
 * User: patmi
 * Date: 16/05/2016
 * Time: 20:27
 */

namespace Masca\EtudiantBundle\Controller\AdminLycee;

use Doctrine\DBAL\Exception\ConstraintViolationException;
use Masca\EtudiantBundle\Entity\MatiereLycee;
use Masca\EtudiantBundle\Repository\MatiereLyceeRepository;
use Masca\EtudiantBundle\Type\MatiereLyceeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;

/**
 * Class AdminMatiereLyceeController
 * @package Masca\EtudiantBundle\Controller\AdminLycee
 * @Route("/lycee/admin/matiere")
 */
class AdminMatiereLyceeController extends Controller
{
    /**
     * @param Request $request
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/liste/{page}", name="liste_matieres_lycee", defaults={"page"=1})
     */
    public function listMatieresAction(Request $request,$page) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $nbParPage = 30;
        /**
         * @var $repository MatiereLyceeRepository
         */
        $repository = $this->getDoctrine()->getManager()
            ->getRepository('MascaEtudiantBundle:MatiereLycee');
        /**
         * @var $matieres MatiereLycee[]
         */
        $matieres = $repository->getMatieres($nbParPage,$page);

        return $this->render('MascaEtudiantBundle:Admin_lycee:list-matiere.html.twig',array(
            'matieres'=>$matieres,
            'page'=> $page,
            'nbPage' => ceil(count($matieres)/$nbParPage)
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/ajouter/", name="ajouter_matiere_lycee")
     */
    public function ajouterMatiereAction(Request $request) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $matiere = new MatiereLycee();

        $matiereForm = $this->createForm(MatiereLyceeType::class, $matiere);

        if($request->getMethod() == 'POST') {
            $matiereForm->handleRequest($request);
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($matiere);
                $em->flush();
            } catch (ConstraintViolationException $e) {
                return $this->render('MascaEtudiantBundle:Admin_lycee:formulaire-matiere.html.twig', array(
                    'form'=>$matiereForm->createView(),
                    'error_message'=>'La matière '.$matiere->getIntitule().' existe déjà, choisissez un autre nom'
                ));
            }
            return $this->redirect($this->generateUrl('liste_matieres_lycee'));
        }
        return $this->render('MascaEtudiantBundle:Admin_lycee:formulaire-matiere.html.twig', array(
            'form'=>$matiereForm->createView()
        ));
    }

    /**
     * @param Request $request
     * @param MatiereLycee $matiere
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @ParamConverter("matiere", options={"mapping": {"matiere_id":"id"}})
     * @Route("/modifier/{matiere_id}", name="modifier_matiere_lycee")
     */
    public function modifierMatiereAction(Request $request, MatiereLycee $matiere) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $matiereForm = $this->createForm(MatiereLyceeType::class, $matiere);
        if($request->getMethod() == 'POST') {
            $matiereForm->handleRequest($request);
            try {
                $this->getDoctrine()->getManager()->flush();
            } catch (ConstraintViolationException $e) {
                return $this->render('MascaEtudiantBundle:Admin_lycee:formulaire-matiere.html.twig', array(
                    'form'=>$matiereForm->createView(),
                    'error_message'=>'La matière '.$matiere->getIntitule().' existe déjà, choisissez un autre nom ou Annuler'
                ));
            }
            return $this->redirect($this->generateUrl('liste_matieres_lycee'));
        }
        return $this->render('MascaEtudiantBundle:Admin_lycee:formulaire-matiere.html.twig', array(
            'form'=>$matiereForm->createView()
        ));
    }

    /**
     * @param Request $request
     * @param MatiereLycee $matiereLycee
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/supprimer/{id}", name="supprime_matiere_lycee")
     */
    public function deleteMatiereLyceeAction(Request $request, MatiereLycee $matiereLycee) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($matiereLycee);
        $em->flush();
        return $this->redirect($this->generateUrl('liste_matieres_lycee'));
    }
}