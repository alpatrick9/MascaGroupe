<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 8/23/16
 * Time: 3:54 PM
 */

namespace Masca\PersonnelBundle\Controller;


use Doctrine\DBAL\Exception\ConstraintViolationException;
use Masca\PersonnelBundle\Entity\AvanceSalaire;
use Masca\PersonnelBundle\Entity\Employer;
use Masca\PersonnelBundle\Type\AvanceSalaireType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class GestionSalaireController
 * @package Masca\PersonnelBundle\Controller
 * @Route("/salaire")
 */
class GestionSalaireController extends Controller
{

    /**
     * @param Request $request
     * @param Employer $employer
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/{id}", name="home_salaire")
     */
    public function indexAction(Request $request, Employer $employer) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')) {
            return $this->render("::message-layout.html.twig", [
                'message' => 'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink' => $request->headers->get('referer')
            ]);
        }

        /**
         * @var Employer[]
         */
        $avances = $this->getDoctrine()->getRepository('MascaPersonnelBundle:AvanceSalaire')->findBy(['employer'=>$employer->getId()]);

        return $this->render('MascaPersonnelBundle:Salaire:index.html.twig', [
            'employer'=>$employer,
            'avances'=>$avances
        ]);
    }

    /**
     * @param Request $request
     * @param Employer $employer
     * @return \Symfony\Component\HttpFoundation\Response$
     * @Route("/avance/{id}", name="add_avance_salaire")
     */
    public function addAvanceSalaireAction(Request $request, Employer $employer) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')) {
            return $this->render("::message-layout.html.twig", [
                'message' => 'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink' => $request->headers->get('referer')
            ]);
        }
        
        $avance = new AvanceSalaire();
        $avance->setEmployer($employer);
        $form = $this->createForm(new AvanceSalaireType($this->getParameter('mois')), $avance);

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $em = $this->getDoctrine()->getManager();
            try {
                $em->persist($avance);
                $em->flush();
            } catch (ConstraintViolationException $e) {
                return $this->render('MascaPersonnelBundle:Salaire:formulaire-avance.html.twig', [
                    'form'=>$form->createView(),
                    'info'=>$avance,
                    'error_message' => 'Ces informations sont déjà enregistées!'
                ]);
            }
            return $this->redirect($this->generateUrl('home_salaire', ['id'=> $employer->getId()]));
        }

        return $this->render('MascaPersonnelBundle:Salaire:formulaire-avance.html.twig', [
            'form'=>$form->createView(),
            'info'=>$avance
        ]);
    }

    /**
     * @param Request $request
     * @param AvanceSalaire $avanceSalaire
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/avance/delete/{id}", name="delete_avance_salaire")
     */
    public function deleteAvanceSalaireAction(Request $request, AvanceSalaire $avanceSalaire) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')) {
            return $this->render("::message-layout.html.twig", [
                'message' => 'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink' => $request->headers->get('referer')
            ]);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($avanceSalaire);
        $em->flush();
        return $this->redirect($this->generateUrl('home_salaire', ['id'=> $avanceSalaire->getEmployer()->getId()]));
    }
}