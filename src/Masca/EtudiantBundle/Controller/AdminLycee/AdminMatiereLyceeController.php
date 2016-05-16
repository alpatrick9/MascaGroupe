<?php
/**
 * Created by PhpStorm.
 * User: patmi
 * Date: 16/05/2016
 * Time: 20:27
 */

namespace Masca\EtudiantBundle\Controller\AdminLycee;

use Masca\EtudiantBundle\Entity\MatiereLycee;
use Masca\EtudiantBundle\Repository\MatiereLyceeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class AdminMatiereLyceeController extends Controller
{
    /**
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/lycee/admin/liste/matiere/{page}", name="liste_matieres_lycee", defaults={"page"=1})
     */
    public function listMatieresAction($page) {
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
     * @Route("/lycee/admin/ajouter/matiere", name="ajouter_matiere_lycee")
     */
    public function ajouterMatiereAction(Request $request) {
        $matiere = new MatiereLycee();
        $matiereFormBuilder = $this->createFormBuilder($matiere);
        $matiereFormBuilder->add('intitule',TextType::class, array(
            'label'=>'Nom du matière'
        ));

        $matiereForm = $matiereFormBuilder->getForm();
        if($request->getMethod() == 'POST') {
            $matiereForm->handleRequest($request);
            if($this->getDoctrine()->getManager()->getRepository('MascaEtudiantBundle:MatiereLycee')
                    ->findOneBy(['intitule'=>$matiere->getIntitule()]) != null) {
                return $this->render('MascaEtudiantBundle:Admin_lycee:ajouter-matiere.html.twig', array(
                    'matiereForm'=>$matiereForm->createView(),
                    'error_message'=>'La matière '.$matiere->getIntitule().' existe déjà, choisissez un autre nom'
                ));
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($matiere);
            $em->flush();
            return $this->redirect($this->generateUrl('liste_matieres_lycee'));
        }
        return $this->render('MascaEtudiantBundle:Admin_lycee:ajouter-matiere.html.twig', array(
            'matiereForm'=>$matiereForm->createView()
        ));
    }

    /**
     * @param Request $request
     * @param MatiereLycee $matiere
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @ParamConverter("matiere", options={"mapping": {"matiere_id":"id"}})
     * @Route("/lycee/admin/modifier/matiere/{matiere_id}", name="modifier_matiere_lycee")
     */
    public function modifierMatiereAction(Request $request, MatiereLycee $matiere) {
        $matiereFormBuilder = $this->createFormBuilder($matiere);
        $matiereFormBuilder->add('intitule',TextType::class, array(
            'label'=>'Nom du matière',
            'data'=>$matiere->getIntitule()
        ));

        $matiereForm = $matiereFormBuilder->getForm();
        if($request->getMethod() == 'POST') {
            $matiereForm->handleRequest($request);
            if($this->getDoctrine()->getManager()->getRepository('MascaEtudiantBundle:MatiereLycee')
                    ->findOneBy(['intitule'=>$matiere->getIntitule()]) != null) {
                return $this->render('MascaEtudiantBundle:Admin_lycee:ajouter-matiere.html.twig', array(
                    'matiereForm'=>$matiereForm->createView(),
                    'error_message'=>'La matière '.$matiere->getIntitule().' existe déjà ou vous n\'avez pas fait de modification, choisissez un autre nom ou Annuler'
                ));
            }
            $this->getDoctrine()->getManager()->flush();
            return $this->redirect($this->generateUrl('liste_matieres_lycee'));
        }
        return $this->render('MascaEtudiantBundle:Admin_lycee:ajouter-matiere.html.twig', array(
            'matiereForm'=>$matiereForm->createView()
        ));
    }
}