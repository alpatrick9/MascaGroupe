<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 9/16/16
 * Time: 9:56 AM
 */

namespace Masca\TresorBundle\Controller\Lycee;

use Doctrine\DBAL\Exception\ConstraintViolationException;
use Masca\TresorBundle\Entity\MvmtLycee;
use Masca\TresorBundle\Entity\SoldeLycee;
use Masca\TresorBundle\Type\MvmtLyceeType;
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
     * @Route("/", name="home_caisse_lycee", defaults={"page" = 1})
     */
    public function indexAction(Request $request, $page) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')) {
            return $this->render("::message-layout.html.twig", [
                'message' => 'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink' => $request->headers->get('referer')
            ]);
        }
        $nbParPage = 30;
        /**
         * @var $mvmts MvmtLycee[]
         */
        $mvmts = $this->getDoctrine()->getRepository('MascaTresorBundle:MvmtLycee')->getMouvements($nbParPage,$page);

        /**
         * @var $solde SoldeLycee
         */
        $solde = $this->getDoctrine()->getRepository('MascaTresorBundle:SoldeLycee')->find(1);

        if(empty($solde)) {
            $solde = new SoldeLycee();
            $em = $this->getDoctrine()->getManager();
            $em->persist($solde);
            $em->flush();
        }
        return $this->render('MascaTresorBundle:Lycee:index.html.twig',[
            'mvmts'=>$mvmts,
            'page'=> $page,
            'nbPage' => ceil(count($mvmts)/$nbParPage),
            'solde'=>$solde
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/new", name="add_mvmt_lycee")
     */
    public function addMvmtLyceeAction(Request $request) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')) {
            return $this->render("::message-layout.html.twig", [
                'message' => 'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink' => $request->headers->get('referer')
            ]);
        }

        $mvmt = new MvmtLycee();

        $form = $this->createForm(new MvmtLyceeType($this->getParameter('operation')),$mvmt);

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if($mvmt->getSomme() <= 0) {
                return $this->render('MascaTresorBundle:Universite:formulaire_operation.html.twig',[
                    'form'=>$form->createView(),
                    'error_message' => 'La somme entrée "'.$mvmt->getSomme().'" n\'est pas valide! '
                ]);
            }
            $em = $this->getDoctrine()->getManager();
            try{
                /**
                 * @var $solde SoldeLycee
                 */
                $solde = $this->getDoctrine()->getRepository('MascaTresorBundle:SoldeLycee')->find(1);
                $solde->setDate($mvmt->getDate());

                $mvmt->setSoldePrecedent($solde->getSolde());

                switch ($mvmt->getTypeOperation()) {
                    case 'd':
                        $solde->setSolde($solde->getSolde() - $mvmt->getSomme());
                        break;
                    case 'c':
                        $solde->setSolde($solde->getSolde() + $mvmt->getSomme());
                        break;
                }
                $mvmt->setSoldeApres($solde->getSolde());
                $em->persist($mvmt);
                $em->flush();

            } catch (ConstraintViolationException $e) {
                return $this->render('MascaTresorBundle:Lycee:formulaire_operation.html.twig',[
                    'form'=>$form->createView(),
                    'error_message' => 'Une erreur d\'enregistrement vient de se produire, veuillez reesseyez svp! '
                ]);
            }
            return $this->redirect($this->generateUrl('home_caisse_lycee'));
        }
        return $this->render('MascaTresorBundle:Lycee:formulaire_operation.html.twig',[
            'form'=>$form->createView()
        ]);
    }
}