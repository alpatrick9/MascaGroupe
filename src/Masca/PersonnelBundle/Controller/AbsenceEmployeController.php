<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 8/25/16
 * Time: 4:05 PM
 */

namespace Masca\PersonnelBundle\Controller;


use Doctrine\DBAL\Exception\ConstraintViolationException;
use Masca\PersonnelBundle\Entity\AbsenceEmploye;
use Masca\PersonnelBundle\Entity\Employer;
use Masca\PersonnelBundle\Type\AbsenceEmployeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AbsenceEmployeController
 * @package Masca\PersonnelBundle\Controller
 * @Route("/absence")
 */
class AbsenceEmployeController extends Controller
{
    /**
     * @param Request $request
     * @param Employer $employer
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/{id}", name="list_absence_employer")
     */
    public function indexAction(Request $request, Employer $employer) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')) {
            return $this->render("::message-layout.html.twig", [
                'message' => 'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink' => $request->headers->get('referer')
            ]);
        }

        /**
         * @var AbsenceEmploye[]
         */
        $listAbsences = $this->getDoctrine()->getManager()->getRepository('MascaPersonnelBundle:AbsenceEmploye')->findBy(['employer'=>$employer]);
        return $this->render('MascaPersonnelBundle:Absence:index.html.twig', [
            'listAbsences'=>$listAbsences,
            'employer'=>$employer
        ]);
    }

    /**
     * @param Request $request
     * @param Employer $employer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/add/{id}", name="add_absence_employer")
     */
    public function addAbsenceAction(Request $request, Employer $employer) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')) {
            return $this->render("::message-layout.html.twig", [
                'message' => 'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink' => $request->headers->get('referer')
            ]);
        }
        $absence = new AbsenceEmploye();
        $form = $this->createForm(new AbsenceEmployeType(), $absence);
        
        if($request->getMethod() == "POST") {
            $form->handleRequest($request);
            $absence->setEmployer($employer);
            $em = $this->getDoctrine()->getManager();
            try{
                $em->persist($absence);
                $em->flush();
            } catch (ConstraintViolationException $e) {
                return $this->render('MascaPersonnelBundle:Absence:formulaire-absence.html.twig', [
                    'form' => $form->createView(),
                    'employer'=>$employer,
                    'error_message' => 'Ces informations sont déjà enregistées!'
                ]);
            }
            return $this->redirect($this->generateUrl('list_absence_employer', ['id'=> $employer->getId()]));
        }
        return $this->render('MascaPersonnelBundle:Absence:formulaire-absence.html.twig', [
            'form' => $form->createView(),
            'employer'=>$employer
        ]);
    }

    /**
     * @param Request $request
     * @param AbsenceEmploye $absenceEmploye
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/edit/{id}", name="edit_absence_employe")
     */
    public function editAbsenceAction(Request $request, AbsenceEmploye $absenceEmploye) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')) {
            return $this->render("::message-layout.html.twig", [
                'message' => 'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink' => $request->headers->get('referer')
            ]);
        }
        $form = $this->createForm(new AbsenceEmployeType(), $absenceEmploye);

        if($request->getMethod() == "POST") {
            $form->handleRequest($request);
            $em = $this->getDoctrine()->getManager();
            try{
                $em->flush();
            } catch (ConstraintViolationException $e) {
                return $this->render('MascaPersonnelBundle:Absence:formulaire-absence.html.twig', [
                    'form' => $form->createView(),
                    'employer'=>$absenceEmploye->getEmployer(),
                    'error_message' => 'Ces informations sont déjà enregistées!'
                ]);
            }
            return $this->redirect($this->generateUrl('list_absence_employer', ['id'=> $absenceEmploye->getEmployer()->getId()]));
        }
        return $this->render('MascaPersonnelBundle:Absence:formulaire-absence.html.twig', [
            'form' => $form->createView(),
            'employer'=>$absenceEmploye->getEmployer()
        ]);
    }

    /**
     * @param Request $request
     * @param AbsenceEmploye $absenceEmploye
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/delete/{id}", name="delete_absence_employer")
     */
    public function deleteAbsenceAction(Request $request, AbsenceEmploye $absenceEmploye) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')) {
            return $this->render("::message-layout.html.twig", [
                'message' => 'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink' => $request->headers->get('referer')
            ]);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($absenceEmploye);
        $em->flush();
        return $this->redirect($this->generateUrl('list_absence_employer', ['id'=> $absenceEmploye->getEmployer()->getId()]));
    }
}