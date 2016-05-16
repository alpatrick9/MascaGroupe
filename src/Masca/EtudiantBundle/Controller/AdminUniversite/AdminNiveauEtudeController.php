<?php
/**
 * Created by PhpStorm.
 * User: patmi
 * Date: 16/05/2016
 * Time: 20:32
 */

namespace Masca\EtudiantBundle\Controller\AdminUniversite;


use Masca\EtudiantBundle\Entity\NiveauEtude;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class AdminNiveauEtudeController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/universite/admin/niveau-etude/", name="niveau_etude_univ")
     */
    public function niveauEtudeAction() {
        return $this->render('MascaEtudiantBundle:Admin_universite:niveau-etude.html.twig',[
            'niveauEtudes'=> $this->getDoctrine()->getManager()
                ->getRepository('MascaEtudiantBundle:NiveauEtude')->findAll()
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/universite/admin/ajoute-niveau-etude/", name="ajouter_niveau_etude_univ")
     */
    public function ajouterNiveauEtudeAction(Request $request) {
        $niveau = new NiveauEtude();
        $formBuilder = $this->createFormBuilder($niveau);
        $formBuilder->add('intitule',TextType::class,[
            'label'=>'Intitulé du niveau'
        ]);
        $form = $formBuilder->getForm();

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $em = $this->getDoctrine()->getManager();
            $em->persist($niveau);
            $em->flush();
            return $this->redirect($this->generateUrl('niveau_etude_univ'));
        }
        return $this->render('MascaEtudiantBundle:Admin_universite:ajouter-niveau-etude.html.twig',[
            'form'=>$form->createView()
        ]);
    }
}