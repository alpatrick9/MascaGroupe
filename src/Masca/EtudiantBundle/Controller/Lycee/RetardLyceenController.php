<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/26/16
 * Time: 11:29 AM
 */

namespace Masca\EtudiantBundle\Controller\Lycee;


use Masca\EtudiantBundle\Entity\Lyceen;
use Masca\EtudiantBundle\Entity\RetardLyceen;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class RetardLyceenController extends Controller
{
    /**
     * @param Lyceen $lyceen
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/lycee/retard/{id}", name="retard_lyceen")
     */
    public function indexAction(Lyceen $lyceen) {
        /**
         * @var $retards RetardLyceen[]
         */
        $retards = $this->getDoctrine()->getManager()->getRepository('MascaEtudiantBundle:RetardLyceen')->findAll();
        
        return $this->render('MascaEtudiantBundle:Lycee:retard.html.twig',[
            'listRetard'=>$retards,
            'lyceen'=>$lyceen
        ]);
    }

}