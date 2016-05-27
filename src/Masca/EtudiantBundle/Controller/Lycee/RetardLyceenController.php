<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/26/16
 * Time: 11:29 AM
 */

namespace Masca\EtudiantBundle\Controller\Lycee;


use Doctrine\DBAL\Exception\ConstraintViolationException;
use Masca\EtudiantBundle\Entity\Lyceen;
use Masca\EtudiantBundle\Entity\RetardLyceen;
use Masca\EtudiantBundle\Type\RetardLyceenType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class RetardLyceenController extends Controller
{
    /**
     * @param Request $request
     * @param Lyceen $lyceen
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/lycee/retard/{id}", name="retard_lyceen")
     */
    public function indexAction(Request $request, Lyceen $lyceen) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_SG')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        /**
         * @var $retards RetardLyceen[]
         */
        $retards = $this->getDoctrine()->getManager()->getRepository('MascaEtudiantBundle:RetardLyceen')->findByLyceen($lyceen);
        
        return $this->render('MascaEtudiantBundle:Lycee:retard.html.twig',[
            'listRetard'=>$retards,
            'lyceen'=>$lyceen
        ]);
    }

    /**
     * @param Request $request
     * @param Lyceen $lyceen
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/lycee/retard/creer/{id}", name="creer_retard_lyceen")
     */
    public function creerRetardAction(Request $request, Lyceen $lyceen) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_SG')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $retard = new RetardLyceen();
        $form = $this->createForm(RetardLyceenType::class,$retard);

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $retard->setLyceen($lyceen);
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($retard);
                $em->flush();
            } catch (ConstraintViolationException $e) {
                return $this->render('MascaEtudiantBundle:Lycee:formulaire-retard.html.twig',[
                    'form'=>$form->createView(),
                    'lyceen'=>$lyceen,
                    'error_message'=>'Ces informations concernant le retard de '.$lyceen->getPerson()->getPrenom().' '.$lyceen->getPerson()->getNom().' est déjà enregistré'
                ]);
            }
            return $this->redirect($this->generateUrl('retard_lyceen',['id'=>$lyceen->getId()]));
        }
        return $this->render('MascaEtudiantBundle:Lycee:formulaire-retard.html.twig',[
            'form'=>$form->createView(),
            'lyceen'=>$lyceen,
        ]);
    }

    /**
     * @param Request $request
     * @param RetardLyceen $retardLyceen
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/lycee/retard/modifier/{id}", name="modifier_retard_lyceen")
     */
    public function modifierRetardAction(Request $request, RetardLyceen $retardLyceen) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_SG')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $form = $this->createForm(RetardLyceenType::class,$retardLyceen);
        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            try {
                $this->getDoctrine()->getManager()->flush();
            } catch (ConstraintViolationException $e) {
                return $this->render('MascaEtudiantBundle:Lycee:formulaire-retard.html.twig',[
                    'form'=>$form->createView(),
                    'lyceen'=>$retardLyceen->getLyceen(),
                    'error_message'=>'Ces informations concernant le retard de '.$retardLyceen->getLyceen()->getPerson()->getPrenom().' '.$retardLyceen->getLyceen()->getPerson()->getNom().' est déjà enregistré'
                ]);
            }
            return $this->redirect($this->generateUrl('retard_lyceen',['id'=>$retardLyceen->getLyceen()->getId()]));
        }
        return $this->render('MascaEtudiantBundle:Lycee:formulaire-retard.html.twig',[
            'form'=>$form->createView(),
            'lyceen'=>$retardLyceen->getLyceen(),
        ]);
    }

    /**
     * @param Request $request
     * @param RetardLyceen $retardLyceen
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/lycee/retard/supprimer/{id}", name="supprimer_retard_lyceen")
     */
    public function supprimerRetardAction(Request $request, RetardLyceen $retardLyceen) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_SG')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($retardLyceen);
        $em->flush();
        return $this->redirect($this->generateUrl('retard_lyceen',['id'=>$retardLyceen->getLyceen()->getId()]));
    }

}