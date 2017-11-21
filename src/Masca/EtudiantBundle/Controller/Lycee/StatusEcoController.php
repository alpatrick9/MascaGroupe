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
        foreach(range(date('Y')-2,date('Y')) as $myyear) {
            $years[$myyear] = $myyear;
        }

        if(empty($session->get('data')))
            $detailsSchoolYear = new DetailsSchoolYear();
        else {
            $detailsSchoolYear = unserialize($session->get('data'));
            $session->remove("data");
        }

        $form = $this->createForm(DetailsSchoolYearType::class, $detailsSchoolYear, [
            'years' => $years,
            'months' => $this->getParameter('mois')
        ]);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            $session->set('data', serialize($detailsSchoolYear));
            return $this->redirect($this->generateUrl('home_state_eco_lycee'));
        }

        return $this->render('MascaEtudiantBundle:Lycee:status_global_eco.html.twig', [
            'form' => $form->createView()
        ]);
    }
}