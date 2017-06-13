<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 8/26/16
 * Time: 2:38 PM
 */

namespace Masca\PersonnelBundle\Controller;


use Doctrine\DBAL\Exception\ConstraintViolationException;
use Masca\PersonnelBundle\Entity\Employer;
use Masca\PersonnelBundle\Entity\PointageEnseignant;
use Masca\PersonnelBundle\Type\PointageEnseignantType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PointageController
 * @package Masca\PersonnelBundle\Controller
 * @Route("/pointage")
 */
class PointageController extends Controller
{
    /**
     * @param Request $request
     * @param Employer $employer
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/list/{id}", name="pointage_home")
     */
    public function indexAction(Request $request, Employer $employer) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')) {
            return $this->render("::message-layout.html.twig", [
                'message' => 'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink' => $request->headers->get('referer')
            ]);
        }

        /**
         * @var PointageEnseignant[]
         */
        $listePointages = $this->getDoctrine()->getManager()->getRepository('MascaPersonnelBundle:PointageEnseignant')->findBy(['employer'=>$employer]);

        return $this->render('MascaPersonnelBundle:Pointage:index.html.twig', [
            'employer'=>$employer,
            'listePointages'=>$listePointages
        ]);
    }

    /**
     * @param Request $request
     * @param Employer $employer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/create/{id}", name="add_pointage")
     */
    public function addPointageAction(Request $request, Employer $employer) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')) {
            return $this->render("::message-layout.html.twig", [
                'message' => 'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink' => $request->headers->get('referer')
            ]);
        }

        $pointage = new PointageEnseignant();
        $form = $this->createForm(new PointageEnseignantType($employer), $pointage);
        $mois = $this->getParameter('mois');
        $count = 1;
        foreach ($mois as $m) {
            if($count == $pointage->getDate()->format('m')) {
                $pointage->setMois($m);
                break;
            }
            $count++;
        }
        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $pointage->setEmployer($employer);
            $pointage->setAnnee($pointage->getDate()->format('YYYY'));
            $em = $this->getDoctrine()->getManager();
            try{
                $em->persist($pointage);
                $em->flush();
            } catch (ConstraintViolationException $e) {
                return $this->render('MascaPersonnelBundle:Pointage:formulaire-pointage.html.twig', [
                    'form' => $form->createView(),
                    'employer'=>$employer,
                    'error_message' => 'Ces informations sont déjà enregistées!'
                ]);
            }
            return $this->redirect($this->generateUrl('pointage_home', ['id'=> $employer->getId()]));
        }

        return $this->render('MascaPersonnelBundle:Pointage:formulaire-pointage.html.twig', [
            'form' => $form->createView(),
            'employer'=>$employer
        ]);
    }

    /**
     * @param Request $request
     * @param PointageEnseignant $pointageEnseignant
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/edit/{id}", name="edit_pointage")
     */
    public function editPointageAction(Request $request, PointageEnseignant $pointageEnseignant) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')) {
            return $this->render("::message-layout.html.twig", [
                'message' => 'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink' => $request->headers->get('referer')
            ]);
        }

        $form = $this->createForm(new PointageEnseignantType($pointageEnseignant->getEmployer()), $pointageEnseignant);

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $pointageEnseignant->setAnnee($pointageEnseignant->getDate()->format('YYYY'));
            $em = $this->getDoctrine()->getManager();
            try{
                $em->flush();
            } catch (ConstraintViolationException $e) {
                return $this->render('MascaPersonnelBundle:Pointage:formulaire-pointage.html.twig', [
                    'form' => $form->createView(),
                    'employer'=>$pointageEnseignant->getEmployer(),
                    'error_message' => 'Ces informations sont déjà enregistées!'
                ]);
            }
            return $this->redirect($this->generateUrl('pointage_home', ['id'=> $pointageEnseignant->getEmployer()->getId()]));
        }

        return $this->render('MascaPersonnelBundle:Pointage:formulaire-pointage.html.twig', [
            'form' => $form->createView(),
            'employer'=>$pointageEnseignant->getEmployer()
        ]);
    }

    /**
     * @param Request $request
     * @param PointageEnseignant $pointageEnseignant
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/delete/{id}", name="delete_pointage")
     */
    public function deletePointageAction(Request $request, PointageEnseignant $pointageEnseignant) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')) {
            return $this->render("::message-layout.html.twig", [
                'message' => 'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink' => $request->headers->get('referer')
            ]);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($pointageEnseignant);
        $em->flush();
        return $this->redirect($this->generateUrl('pointage_home', ['id'=> $pointageEnseignant->getEmployer()->getId()]));
    }
}