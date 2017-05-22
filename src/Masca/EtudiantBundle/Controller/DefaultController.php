<?php

namespace Masca\EtudiantBundle\Controller;

use Masca\EtudiantBundle\Entity\Lyceen;
use Masca\EtudiantBundle\Entity\LyceenRepository;
use Masca\EtudiantBundle\Entity\Universitaire;
use Masca\EtudiantBundle\Entity\UniversitaireSonFiliere;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

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

    /**
     * @return Response
     * @Route("/update-data", name="update_data")
     */
    public function updateDataAction() {
        /**
         * @var $l Lyceen[]
         */
        $l = $this->getDoctrine()->getRepository("MascaEtudiantBundle:Lyceen")->findAll();

        /**
         * @var $u UniversitaireSonFiliere[]
         */
        $u = $this->getDoctrine()->getRepository("MascaEtudiantBundle:UniversitaireSonFiliere")->findAll();

        foreach($l as $lyceen) {
            $lyceen->setDroitInscription(false);
        }
        foreach($u as $universitaire) {
            $universitaire->setDroitInscription(false);
        }

        $this->getDoctrine()->getManager()->flush();
        return new Response("data update");
    }

}
