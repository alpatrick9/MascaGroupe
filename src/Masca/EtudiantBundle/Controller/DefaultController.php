<?php

namespace Masca\EtudiantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class DefaultController
 * @package Masca\EtudiantBundle\Controller
 * @Route("/")
 */
class DefaultController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/accueil", name="etudiant_home")
     */
    public function indexAction()
    {
        return $this->render('MascaEtudiantBundle:Default:index.html.twig');
    }

}
