<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/26/16
 * Time: 10:24 AM
 */

namespace Masca\EtudiantBundle\Controller\Lycee;


use Doctrine\DBAL\Exception\ConstraintViolationException;
use Masca\EtudiantBundle\Entity\AbsenceLyceen;
use Masca\EtudiantBundle\Entity\Lyceen;
use Masca\EtudiantBundle\Type\AbsenceLyceenType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AbsentLyceenController
 * @package Masca\EtudiantBundle\Controller\Lycee
 * @Route("/lycee/absence")
 */
class AbsentLyceenController extends Controller
{
    /**
     * @param Request $request
     * @param Lyceen $lyceen
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/{id}", name="absence_lyceen")
     */
    public function indexAction(Request $request,Lyceen $lyceen) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_SG')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        /**
         * @var $listAbs AbsenceLyceen[]
         */
        $listAbs = $this->getDoctrine()->getManager()->getRepository('MascaEtudiantBundle:AbsenceLyceen')->findByLyceen($lyceen);
        return $this->render('MascaEtudiantBundle:Lycee:absence.html.twig', [
            'listAbs'=> $listAbs,
            'lyceen'=>$lyceen
        ]);
    }

    /**
     * @param Request $request
     * @param Lyceen $lyceen
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/enregistrement/{id}", name="absence_enregistrement_lyceen")
     */
    public function enregistrementAbsenceAction(Request $request, Lyceen $lyceen) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_SG')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $absence = new AbsenceLyceen();
        $form = $this->createForm(AbsenceLyceenType::class, $absence);

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $absence->setLyceen($lyceen);
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($absence);
                $em->flush();
            } catch (ConstraintViolationException $e) {
                return $this->render('MascaEtudiantBundle:Lycee:formulaire-absence.html.twig',[
                    'form'=>$form->createView(),
                    'lyceen'=>$lyceen,
                    'error_message'=>'L\'abcense avec ces information pour '.$lyceen->getPerson()->getPrenom().' '.$lyceen->getPerson()->getNom().' est déjà enregistré'
                ]);
            }
            return $this->redirect($this->generateUrl('absence_lyceen',['id'=>$lyceen->getId()]));
        }

        return $this->render('MascaEtudiantBundle:Lycee:formulaire-absence.html.twig',[
           'form'=>$form->createView(),
            'lyceen'=>$lyceen
        ]);
    }

    /**
     * @param Request $request
     * @param AbsenceLyceen $absenceLyceen
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/modifier/{id}", name="absence_modifier_lyceen")
     */
    public function modificationAbsenceAction(Request $request, AbsenceLyceen $absenceLyceen) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_SG')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $form = $this->createForm(AbsenceLyceenType::class, $absenceLyceen);
        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            try {
                $this->getDoctrine()->getManager()->flush();
            }  catch (ConstraintViolationException $e) {
                return $this->render('MascaEtudiantBundle:Lycee:formulaire-absence.html.twig',[
                    'form'=>$form->createView(),
                    'lyceen'=>$absenceLyceen->getLyceen(),
                    'error_message'=>'L\'abcense avec ces information pour '.$absenceLyceen->getLyceen()->getPerson()->getPrenom().' '.$absenceLyceen->getLyceen()->getPerson()->getNom().' est déjà enregistré'
                ]);
            }
            return $this->redirect($this->generateUrl('absence_lyceen',['id'=>$absenceLyceen->getLyceen()->getId()]));
        }
        return $this->render('MascaEtudiantBundle:Lycee:formulaire-absence.html.twig',[
            'form'=>$form->createView(),
            'lyceen'=>$absenceLyceen->getLyceen()
        ]);
    }

    /**
     * @param Request $request
     * @param AbsenceLyceen $absenceLyceen
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/supprimer/{id}", name="absence_supprimer_lyceen")
     */
    public function supprimerAbsenceAction(Request $request, AbsenceLyceen $absenceLyceen) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_SG')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($absenceLyceen);
        $em->flush();
        return $this->redirect($this->generateUrl('absence_lyceen',['id'=>$absenceLyceen->getLyceen()->getId()]));
    }

}