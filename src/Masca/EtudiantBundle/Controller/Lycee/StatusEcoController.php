<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/13/17
 * Time: 10:36 AM
 */

namespace Masca\EtudiantBundle\Controller\Lycee;

use Masca\EtudiantBundle\Entity\Classe;
use Masca\EtudiantBundle\Entity\FraisScolariteLyceen;
use Masca\EtudiantBundle\Entity\Lyceen;
use Masca\EtudiantBundle\Model\DetailsSchoolYear;
use Masca\EtudiantBundle\Type\DetailsSchoolYearType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class StatusEcoController
 * @package Masca\EtudiantBundle\Controller\Lycee
 * @Route("state/eco")
 */
class StatusEcoController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="home_state_eco_lycee")
     */
    public function index(Request $request) {
        $session = $this->get('session');
        $years = [];
        $months = [];
        $haveData = false;
        foreach(range(date('Y')-2,date('Y')) as $myyear) {
            $years[$myyear] = $myyear;
        }

        if(empty($session->get('data')))
            $detailsSchoolYear = new DetailsSchoolYear();
        else {
            $haveData = true;
            /**
             * @var $detailsSchoolYear DetailsSchoolYear
             */
            $detailsSchoolYear = unserialize($session->get('data'));

            for($i = $this->convertMonthToInt($detailsSchoolYear->getStartMonth()); $i < 12; $i++) {
                $months[] = $this->convertIntToMonth($i);
            }

            for($j = 0 ; $j <= $this->convertMonthToInt($detailsSchoolYear->getEndMonth()); $j++) {
                $months[] = $this->convertIntToMonth($j);
            }

            /**
             * @var $classe Classe
             */
            $classe = $this->getDoctrine()->getManager()->merge($detailsSchoolYear->getClasse());
            $detailsSchoolYear->setClasse($classe);
            $session->remove("data");
        }

        $form = $this->createForm(DetailsSchoolYearType::class, $detailsSchoolYear, [
            'years' => $years,
            'months' => $this->getParameter('mois')
        ]);

        /**
         * @var $lyceens Lyceen[]
         */
        $lyceens = [];
        $status = [];
        if($haveData) {
            $lyceens = $this->getDoctrine()->getManager()->getRepository("MascaEtudiantBundle:Lyceen")
                ->findBy([
                    "sonClasse" => $detailsSchoolYear->getClasse(),
                    "anneeScolaire" => $detailsSchoolYear->getStartYear()."-".($detailsSchoolYear->getStartYear()+1)
                    ],
                    [
                        "numeros" => "ASC"
                    ]);

            foreach ($lyceens as $lyceen) {
                $status[$lyceen->getId()] = [];
                $ecoRepository = $this->getDoctrine()->getRepository("MascaEtudiantBundle:FraisScolariteLyceen");
                for($i = $this->convertMonthToInt($detailsSchoolYear->getStartMonth()); $i < 12; $i++) {
                    /**
                     * @var $ecolage FraisScolariteLyceen
                     */
                    $ecolage = $ecoRepository->findOneBy([
                        'mois' => $this->convertIntToMonth($i),
                        'annee' => $detailsSchoolYear->getStartYear(),
                        'lyceen' => $lyceen
                    ]);
                    $status[$lyceen->getId()][] = $ecolage == null ? false : $ecolage->getStatus();
                }
                for($j = 0 ; $j <= $this->convertMonthToInt($detailsSchoolYear->getEndMonth()); $j++) {
                    /**
                     * @var $ecolage FraisScolariteLyceen
                     */
                    $ecolage = $ecoRepository->findOneBy([
                        'mois' => $this->convertIntToMonth($j),
                        'annee' => $detailsSchoolYear->getStartYear()+1,
                        'lyceen' => $lyceen
                    ]);
                    $status[$lyceen->getId()][] = $ecolage == null ? false : $ecolage->getStatus();
                }
            }
        }

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            $session->set('data', serialize($detailsSchoolYear));
            return $this->redirect($this->generateUrl('home_state_eco_lycee'));
        }

        return $this->render('MascaEtudiantBundle:Lycee:status_global_eco.html.twig', [
            'form' => $form->createView(),
            'haveData' => $haveData,
            'lyceens' => $lyceens,
            'months' => $months,
            'status' => $status
        ]);
    }

    protected function convertMonthToInt($month) {
        $months = $this->months();
        foreach ($months as $key => $value) {
            if($value == $month)
                return $key;
        }
    }

    protected function convertIntToMonth($month) {
        return $this->months()[$month];
    }

    protected function months() {
        $months = $this->getParameter("mois");
        $month_array = [];
        foreach ($months as $m) {
            $month_array[] = $m;
        }
        return $month_array;
    }
}