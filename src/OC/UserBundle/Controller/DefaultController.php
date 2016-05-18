<?php
/**
 * Created by PhpStorm.
 * User: patmi
 * Date: 02/05/2016
 * Time: 10:03
 */

namespace OC\UserBundle\Controller;


use OC\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="oc_user_homepage")
     */
    public function indexAction() {
        return $this->render('MascaEtudiantBundle:Default:index.html.twig');
    }

    public function listUserAction() {
        /**
         * @var $users User[]
         */
        $users = $this->getDoctrine()->getManager()
            ->getRepository('OCUserBundle:User')->findAll();
        return $this->render('OCUserBundle:Default:list-users.html.twig', array(
            'users'=> $this->getDoctrine()->getManager()
                            ->getRepository('OCUserBundle:User')->findAll()
        ));
    }

}