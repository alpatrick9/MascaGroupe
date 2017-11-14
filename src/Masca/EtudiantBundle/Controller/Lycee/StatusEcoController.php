<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/13/17
 * Time: 10:36 AM
 */

namespace Masca\EtudiantBundle\Controller\Lycee;

use Masca\EtudiantBundle\Model\DetailsSchoolYear;
use Masca\EtudiantBundle\Type\DetailsSchoolYearType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

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
    public function index() {
        $years = [];
        foreach(range(date('Y')-2,date('Y')) as $myyear) {
            $years[$myyear] = $myyear;
        }
        $detailsSchoolYear = new DetailsSchoolYear();
        $form = $this->createForm(DetailsSchoolYearType::class, $detailsSchoolYear, [
            'years' => $years,
            'months' => $this->getParameter('mois')
        ]);

        return $this->render('MascaEtudiantBundle:Lycee:status_global_eco.html.twig', [
            'form' => $form->createView()
        ]);
    }
}