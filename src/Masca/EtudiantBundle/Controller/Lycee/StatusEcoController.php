<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/13/17
 * Time: 10:36 AM
 */

namespace Masca\EtudiantBundle\Controller\Lycee;

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
     * @param null $startYear
     * @param null $endYear
     * @param null $startMonth
     * @param null $endMonth
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="home_state_eco_lycee")
     */
    public function index($startYear = null, $endYear = null, $startMonth = null, $endMonth = null) {
        $formBuilder = $this->createFormBuilder();
        $years = [];
        foreach(range(date('Y')-2,date('Y')) as $myyear) {
            $years[$myyear] = $myyear;
        }
        $form = $formBuilder
            ->add('startMonth', ChoiceType::class, [
                'label' => 'à partir du :',
                'choices' => $this->getParameter('mois'),
                'choices_as_values' => true
            ])
            ->add('startYear', ChoiceType::class, [
                'label' => ' ',
                'choices' => $years,
                'choices_as_values' => true
            ])
            ->add('endMonth', ChoiceType::class, [
                'label' => 'jusqu\'au:',
                'choices' => $this->getParameter('mois'),
                'choices_as_values' => true
            ])
            ->add('endYear', ChoiceType::class, [
                'label' => ' ',
                'choices' => $years,
                'choices_as_values' => true
            ]);
        return $this->render('MascaEtudiantBundle:Lycee:status_global_eco.html.twig', [
            'form' => $form->getForm()->createView()
        ]);
    }
}