<?php
/**
 * Created by PhpStorm.
 * User: patmi
 * Date: 16/05/2016
 * Time: 20:34
 */

namespace Masca\EtudiantBundle\Controller\AdminUniversite;


use Masca\EtudiantBundle\Entity\Semestre;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AdminSemestreController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/universite/admin/semestre/", name="semestre_univ")
     */
    public function semestreAction() {
        return $this->render('MascaEtudiantBundle:Admin_universite:semestre.html.twig',[
            'semestres'=>$this->getDoctrine()->getManager()
                ->getRepository('MascaEtudiantBundle:Semestre')->findAll()
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/universite/admin/ajoute-semestre/", name="ajouter_semestre_univ")
     */
    public function ajouterSemestreAction(Request $request) {
        $semestre = new Semestre();
        $formBuilder = $this->createFormBuilder($semestre);
        $formBuilder->add('intitule',TextType::class,[
            'label'=>'Intituler du semestre',
            'attr'=>['placeholder'=>'Semestre 1']
        ]);
        $form = $formBuilder->getForm();

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $em = $this->getDoctrine()->getManager();
            $em->persist($semestre);
            $em->flush();
            return $this->redirect($this->generateUrl('semestre_univ'));
        }
        return $this->render('MascaEtudiantBundle:Admin_universite:formulaire-semestre.html.twig',[
            'form'=>$form->createView()
        ]);
    }

}