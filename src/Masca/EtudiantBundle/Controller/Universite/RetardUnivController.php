<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/26/16
 * Time: 1:56 PM
 */

namespace Masca\EtudiantBundle\Controller\Universite;


use Doctrine\DBAL\Exception\ConstraintViolationException;
use Masca\EtudiantBundle\Entity\RetardUniv;
use Masca\EtudiantBundle\Entity\Universitaire;
use Masca\EtudiantBundle\Type\RetardUnivType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class RetardUnivController extends Controller
{

    /**
     * @param Universitaire $universitaire
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/universite/retard/{id}", name="retard_universitaire")
     */
    public function indexAction(Universitaire $universitaire) {
        /**
         * @var $retards RetardUniv[]
         */
        $retards = $this->getDoctrine()->getManager()->getRepository('MascaEtudiantBundle:RetardUniv')->findByUniversitaire($universitaire);

        return $this->render('MascaEtudiantBundle:Universite:retard.html.twig',[
            'listRetard'=>$retards,
            'universitaire'=>$universitaire
        ]);
    }

    /**
     * @param Request $request
     * @param Universitaire $universitaire
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/universite/retard/creer/{id}", name="enregistrement_retard_universitaire")
     */
    public function creerRetardAction(Request $request, Universitaire $universitaire) {
        $retard = new RetardUniv();
        $form = $this->createForm(RetardUnivType::class,$retard);

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $retard->setUniversitaire($universitaire);
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($retard);
                $em->flush();
            } catch (ConstraintViolationException $e) {
                return $this->render('MascaEtudiantBundle:Universite:formulaire-retard.html.twig',[
                    'form'=>$form->createView(),
                    'universitaire'=>$universitaire,
                    'error_message'=>'Ces informations concernant le retard de '.$universitaire->getPerson()->getPrenom().' '.$universitaire->getPerson()->getNom().' est déjà enregistré'
                ]);
            }
            return $this->redirect($this->generateUrl('retard_universitaire',['id'=>$universitaire->getId()]));
        }
        return $this->render('MascaEtudiantBundle:Universite:formulaire-retard.html.twig',[
            'form'=>$form->createView(),
            'universitaire'=>$universitaire,
        ]);
    }

    /**
     * @param Request $request
     * @param RetardUniv $retardUniv
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/universite/retard/modifier/{id}", name="modifier_retard_universitaire")
     */
    public function modifierRetardAction(Request $request, RetardUniv $retardUniv) {
        $form = $this->createForm(RetardUnivType::class,$retardUniv);
        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            try {
                $this->getDoctrine()->getManager()->flush();
            } catch (ConstraintViolationException $e) {
                return $this->render('MascaEtudiantBundle:Universite:formulaire-retard.html.twig',[
                    'form'=>$form->createView(),
                    'universitaire'=>$retardUniv->getUniversitaire(),
                    'error_message'=>'Ces informations concernant le retard de '.$retardUniv->getUniversitaire()->getPerson()->getPrenom().' '.$retardUniv->getUniversitaire()->getPerson()->getNom().' est déjà enregistré'
                ]);
            }
            return $this->redirect($this->generateUrl('retard_universitaire',['id'=>$retardUniv->getUniversitaire()->getId()]));
        }
        return $this->render('MascaEtudiantBundle:Universite:formulaire-retard.html.twig',[
            'form'=>$form->createView(),
            'universitaire'=>$retardUniv->getUniversitaire(),
        ]);
    }

    /**
     * @param RetardUniv $retardUniv
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/universite/retard/supprimer/{id}", name="supprimer_retard_universitaire")
     */
    public function supprimerRetardAction(RetardUniv $retardUniv) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($retardUniv);
        $em->flush();
        return $this->redirect($this->generateUrl('retard_universitaire',['id'=>$retardUniv->getUniversitaire()->getId()]));
    }
}