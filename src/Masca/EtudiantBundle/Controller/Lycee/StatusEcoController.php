<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/13/17
 * Time: 10:36 AM
 */

namespace Masca\EtudiantBundle\Controller\Lycee;

use Masca\EtudiantBundle\Entity\Classe;
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
        if($haveData) {
            $lyceens = $this->getDoctrine()->getManager()->getRepository("MascaEtudiantBundle:Lyceen")
                ->findBy([
                    "sonClasse" => $detailsSchoolYear->getClasse(),
                    "anneeScolaire" => $detailsSchoolYear->getStartYear()."-".($detailsSchoolYear->getStartYear()+1)
                    ],
                    [
                        "numeros" => "ASC"
                    ]);
        }

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            $session->set('data', serialize($detailsSchoolYear));
            return $this->redirect($this->generateUrl('home_state_eco_lycee'));
        }

        return $this->render('MascaEtudiantBundle:Lycee:status_global_eco.html.twig', [
            'form' => $form->createView(),
            'haveData' => $haveData,
            'lyceens' => $lyceens
        ]);
    }
}