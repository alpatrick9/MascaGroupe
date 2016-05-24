<?php
/**
 * Created by PhpStorm.
 * User: patmi
 * Date: 16/05/2016
 * Time: 20:34
 */

namespace Masca\EtudiantBundle\Controller\AdminUniversite;


use Doctrine\DBAL\Exception\ConstraintViolationException;
use Masca\EtudiantBundle\Entity\Semestre;
use Masca\EtudiantBundle\Type\SemestreType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
        $form = $this->createForm(SemestreType::class, $semestre);

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($semestre);
                $em->flush();
            } catch (ConstraintViolationException $e) {
                return $this->render('MascaEtudiantBundle:Admin_universite:formulaire-semestre.html.twig',[
                    'form'=>$form->createView(),
                    'error_message'=>'La semestre '.$semestre->getIntitule().' existe déjà'
                ]);
            }
            return $this->redirect($this->generateUrl('semestre_univ'));
        }
        return $this->render('MascaEtudiantBundle:Admin_universite:formulaire-semestre.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/universite/admin/modifier-semestre/{semestre_id}", name="modifier_semestre_univ")
     * @ParamConverter("semestre", options={"mapping": {"semestre_id":"id"}})
     */
    public function midifierSemestreAction(Request $request,Semestre $semestre) {
        $form = $this->createForm(SemestreType::class, $semestre);

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            try {
                $this->getDoctrine()->getManager()->flush();
            } catch (ConstraintViolationException $e) {
                return $this->render('MascaEtudiantBundle:Admin_universite:formulaire-semestre.html.twig',[
                    'form'=>$form->createView(),
                    'error_message'=>'La semestre '.$semestre->getIntitule().' existe déjà'
                ]);
            }
            return $this->redirect($this->generateUrl('semestre_univ'));
        }
        return $this->render('MascaEtudiantBundle:Admin_universite:formulaire-semestre.html.twig',[
            'form'=>$form->createView()
        ]);
    }

}