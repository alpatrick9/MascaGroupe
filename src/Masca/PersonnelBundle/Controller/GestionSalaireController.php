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
use Masca\PersonnelBundle\Entity\InfoSalaireFixe;
use Masca\PersonnelBundle\Entity\PointageEnseignant;
use Masca\PersonnelBundle\Entity\Salaire;
use Masca\PersonnelBundle\Type\AvanceSalaireType;
use Masca\PersonnelBundle\Type\SalaireType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\Tests\Fixtures\TypeHinted;

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

        /**
         * @var Salaire[]
         */
        $salaires = $this->getDoctrine()->getRepository('MascaPersonnelBundle:Salaire')->findBy(['employer'=>$employer->getId()]);

        return $this->render('MascaPersonnelBundle:Salaire:index.html.twig', [
            'employer'=>$employer,
            'avances'=>$avances,
            'salaires'=>$salaires
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

    /**
     * @param Request $request
     * @param Employer $employer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/payement/{id}", name="payement_salaire")
     */
    public function addSalaireAction(Request $request, Employer $employer) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')) {
            return $this->render("::message-layout.html.twig", [
                'message' => 'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink' => $request->headers->get('referer')
            ]);
        }

        $salaire = new Salaire();
        $salaire->setEmployer($employer);
        
        $form = $this->createForm(new SalaireType($this->getParameter('mois')), $salaire);

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            /**
             * @var $infoSalaireFixes InfoSalaireFixe[]
             */
            $infoSalaireFixes = $this->getDoctrine()->getRepository('MascaPersonnelBundle:InfoSalaireFixe')->findAllPostFixe($employer);
            
            $salaireFixes = [];
            
            foreach ($infoSalaireFixes as $fix) {
                array_push($salaireFixes, $fix->getSalaire());
            }
            $salaire->setDetailSalaireFixe($salaireFixes);

            /**
             * @var $avanceSalaires AvanceSalaire[]
             */
            $avanceSalaires = $this->getDoctrine()->getRepository('MascaPersonnelBundle:AvanceSalaire')->findBy(['employer'=>$employer, 'mois' => $salaire->getMois(), 'annee'=>$salaire->getAnnee()]);

            foreach ($avanceSalaires as $avanceSalaire) {
                $salaire->setTotalAvance($salaire->getTotalAvance() + $avanceSalaire->getSomme());
            }

            /**
             * @var $pointages PointageEnseignant[]
             */
            $pointages = $this->getDoctrine()->getRepository('MascaPersonnelBundle:PointageEnseignant')->findPointageBy($salaire->getMois(), $salaire->getAnnee(), $employer);

            $salaireHoraires = [];

            foreach ($pointages as $pointage) {
                $salaire->setTotalHeures($salaire->getTotalHeures() + $pointage->getVolumeHoraire());
                if(empty($salaireHoraires[$pointage->getInfoTauxHoraire()->getTauxHoraire()])) {
                    $salaireHoraires[$pointage->getInfoTauxHoraire()->getTauxHoraire()] = $pointage->getVolumeHoraire();
                }
                else {
                    $salaireHoraires[$pointage->getInfoTauxHoraire()->getTauxHoraire()] += $pointage->getVolumeHoraire();
                }

            }
            $salaire->setDetailSalaireHoraire($salaireHoraires);

            $em = $this->getDoctrine()->getManager();
            try {
                $em->persist($salaire);
                $em->flush();
            } catch (ConstraintViolationException $e) {
                return $this->render('MascaPersonnelBundle:Salaire:formulaire-salaire.html.twig', [
                    'form'=>$form->createView(),
                    'info'=>$salaire,
                    'error_message' => 'Ces informations sont déjà enregistées!'
                ]);
            }
            return $this->redirect($this->generateUrl('home_salaire', ['id'=> $employer->getId()]));
        }

        return $this->render('MascaPersonnelBundle:Salaire:formulaire-salaire.html.twig', [
            'form'=>$form->createView(),
            'info'=>$salaire
        ]);
    }

    /**
     * @param Request $request
     * @param Salaire $salaire
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/details/{id}", name="detail_salaire")
     */
    public function detailsSalaireAction(Request $request, Salaire $salaire) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')) {
            return $this->render("::message-layout.html.twig", [
                'message' => 'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink' => $request->headers->get('referer')
            ]);
        }
        $salaireBrute = 0;

        $totalSalaireFixe = 0;

        foreach ($salaire->getDetailSalaireFixe() as $somme) {
            $totalSalaireFixe += $somme;
        }

        $salaireBrute += $totalSalaireFixe;

        $totalHoraires = [];
        foreach ($salaire->getDetailSalaireHoraire() as $key=>$value) {
            $totalHoraires[$key] = $key*$value;
            $salaireBrute += $totalHoraires[$key];
        }

        $retenuCnaps = ($salaireBrute * $salaire->getEmployer()->getTauxCnaps())/100;

        $salaireNet = $salaireBrute - $retenuCnaps -$salaire->getTotalAvance();
        
        return $this->render('MascaPersonnelBundle:Salaire:details-salaire.html.twig', [
            'salaire'=>$salaire,
            'salaireFixeBrute'=> $totalSalaireFixe,
            'salaireHoraireBrutes'=>$totalHoraires,
            'cnaps'=>$retenuCnaps,
            'totalBrute'=>$salaireBrute,
            'salaireNet'=>$salaireNet
        ]);
    }
}