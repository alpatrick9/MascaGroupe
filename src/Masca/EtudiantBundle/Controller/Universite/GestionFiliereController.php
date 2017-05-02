<?php
/**
 * Created by PhpStorm.
 * User: patmi
 * Date: 16/05/2016
 * Time: 20:48
 */

namespace Masca\EtudiantBundle\Controller\Universite;


use Doctrine\DBAL\Exception\ConstraintViolationException;
use Masca\EtudiantBundle\Entity\Universitaire;
use Masca\EtudiantBundle\Entity\UniversitaireSonFiliere;
use Masca\EtudiantBundle\Repository\FraisScolariteUnivRepository;
use Masca\EtudiantBundle\Type\SonFiliereType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class GestionFiliereController
 * @package Masca\EtudiantBundle\Controller\Universite
 * @Route("/universite/filiere")
 */
class GestionFiliereController extends Controller
{
    /**
     * @param Request $request
     * @param UniversitaireSonFiliere $sonFiliere
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/reinscription/{id}", name="reinscription_universite")
     */
    public function reinscriptionAction(Request $request, UniversitaireSonFiliere $sonFiliere) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_ECO_U')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $sonFiliereForm = $this->createForm(SonFiliereType::class, $sonFiliere);
        /**
         * @var $ecolageRepository FraisScolariteUnivRepository
         */
        $ecolageRepository = $this->getDoctrine()->getManager()->getRepository('MascaEtudiantBundle:FraisScolariteUniv');

        if($ecolageRepository->statusEcolage($sonFiliere->getUniversitaire())) {
            return $this->render('MascaEtudiantBundle:Universite:details-etude.html.twig',[
                'sonFiliere'=>$sonFiliere,
                'error_message'=>'Reinscription refuser. Il reste encore des frais de scolarités qui ne sont pas encore regularisés pour l\'année d\'etude '.$sonFiliere->getAnneeEtude()
            ]);
        }
        if($request->getMethod() == 'POST') {
            $sonFiliereForm->handleRequest($request);
            $sonFiliere->setDroitInscription(false);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirect($this->generateUrl('details_etude_universitaire', array('sonFiliere_id'=>$sonFiliere->getId())));
        }

        return $this->render('MascaEtudiantBundle:Universite:reinscription.html.twig',array(
            'sonFiliereForm'=>$sonFiliereForm->createView(),
            'fullEtudantName'=> $sonFiliere->getUniversitaire()->getPerson()->getPrenom() ." ".$sonFiliere->getUniversitaire()->getPerson()->getNom(),
            'universitaireId'=>$sonFiliere->getUniversitaire()->getId(),
            'sonFiliereId'=> $sonFiliere->getId()
        ));
    }

    /**
     * @param Request $request
     * @param UniversitaireSonFiliere $sonFiliere
     * @return \Symfony\Component\HttpFoundation\Response
     * @ParamConverter("sonFiliere", options={"mapping": {"sonFiliere_id":"id"}})
     * @Route("/details-etude/{sonFiliere_id}", name="details_etude_universitaire")
     */
    public function detailsEtudeAction(Request $request,UniversitaireSonFiliere $sonFiliere) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_USER_U')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        return $this->render('MascaEtudiantBundle:Universite:details-etude.html.twig',[
            'sonFiliere'=>$sonFiliere
        ]);
    }

    /**
     * @param Request $request
     * @param UniversitaireSonFiliere $universitaireSonFiliere
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/supprimer-detail-filiere/{id}", name="supprimer_detail_filiere_univ")
     */
    public function supprimerDetailsFiliereAction(Request $request, UniversitaireSonFiliere $universitaireSonFiliere) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($universitaireSonFiliere);
        $em->flush();
        return $this->redirect($this->generateUrl('details_universite',array('id'=>$universitaireSonFiliere->getUniversitaire()->getId())));
    }

    /**
     * @param Request $request
     * @param Universitaire $universitaire
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/ajouter/filiere/{id}", name="ajouter_filiere_etudiant_univ")
     */
    public function ajouteFilièreAction(Request $request, Universitaire $universitaire) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $sonFiliere = new UniversitaireSonFiliere();
        $sonFiliereForm = $this->createForm(SonFiliereType::class, $sonFiliere);

        if($request->getMethod() == 'POST') {
            $sonFiliereForm->handleRequest($request);
            $sonFiliere->setUniversitaire($universitaire);
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($sonFiliere);
                $em->flush();
            } catch (ConstraintViolationException $e) {
                return $this->render('MascaEtudiantBundle:Universite:add-filiere.html.twig',array(
                    'sonFiliereForm'=>$sonFiliereForm->createView(),
                    'universitaire'=>$universitaire,
                    'error_message'=>"L'étudiant ".$universitaire->getPerson()->getNom()." ".$universitaire->getPerson()->getPrenom()." est déjà inscrit en ".$sonFiliere->getSonFiliere()->getIntitule()
                ));
            }
            return $this->redirect($this->generateUrl('details_universite',array('id'=>$universitaire->getId())));
        }

        return $this->render('MascaEtudiantBundle:Universite:add-filiere.html.twig',array(
            'sonFiliereForm'=>$sonFiliereForm->createView(),
            'universitaire'=>$universitaire
        ));
    }
}