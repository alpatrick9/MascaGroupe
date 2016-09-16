<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 9/16/16
 * Time: 9:56 AM
 */

namespace Masca\TresorBundle\Controller\Lycee;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CaisseLyceeController
 * @package Masca\TresorBundle\Controller\Universite
 * @Route("/lycee")
 */
class CaisseLyceeController extends Controller
{

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="home_caisse_lycee")
     */
    public function indexAction(Request $request) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')) {
            return $this->render("::message-layout.html.twig", [
                'message' => 'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink' => $request->headers->get('referer')
            ]);
        }
        return $this->render('MascaTresorBundle:Lycee:index.html.twig');
    }
}