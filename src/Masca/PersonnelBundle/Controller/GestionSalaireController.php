<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 8/23/16
 * Time: 3:54 PM
 */

namespace Masca\PersonnelBundle\Controller;


use Masca\PersonnelBundle\Entity\Employer;
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
        return $this->render('MascaPersonnelBundle:Salaire:index.html.twig', [
            'employer'=>$employer
        ]);
    }
}