<?php
/**
 * Created by PhpStorm.
 * User: patmi
 * Date: 25/04/2016
 * Time: 19:51
 */

namespace Masca\EtudiantBundle\Controller\Universite;


use Masca\EtudiantBundle\Entity\InfoEtudiant;
use Masca\EtudiantBundle\Entity\Person;
use Masca\EtudiantBundle\Entity\Universitaire;
use Masca\EtudiantBundle\Entity\UniversitaireRepository;
use Masca\EtudiantBundle\Entity\UniversitaireSonFiliere;
use Masca\EtudiantBundle\Type\InfoEtudiantType;
use Masca\EtudiantBundle\Type\PersonType;
use Masca\EtudiantBundle\Type\SonFiliereType;
use Masca\EtudiantBundle\Type\UniversitaireType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class UniversiteController
 * @package Masca\EtudiantBundle\Controller\Universite
 * @Route("/universite")
 */
class UniversiteController extends Controller
{
    /**
     * @param Request $request
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/accueil/{page}", name="accueil_universite", defaults={"page" = 1})
     */
    public function indexAction(Request $request, $page) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_USER')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $nbParPage = 30;
        /**
         * @var $repository UniversitaireRepository
         */
        $repository = $this->getDoctrine()->getManager()
            ->getRepository('MascaEtudiantBundle:Universitaire');
        /**
         * @var $universitaires Universitaire[]
         */
        $universitaires = $repository->getUniversitiares($nbParPage,$page);

        return $this->render('MascaEtudiantBundle:Universite:index.html.twig',array(
            'universitaires'=>$universitaires,
            'page'=> $page,
            'nbPage' => ceil(count($universitaires)/$nbParPage)
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/inscription/", name="inscription_universite")
     */
    public  function inscriptionAction(Request $request) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_SECRETAIRE')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $person = new Person();
        $personForm = $this->createForm(PersonType::class, $person);
        
        $infoEtudiant = new InfoEtudiant();
        $infoEtudiantForm = $this->createForm(InfoEtudiantType::class, $infoEtudiant);
        
        $universitaire = new Universitaire();
        $universitaireForm = $this->createForm(UniversitaireType::class, $universitaire);
        
        $sonFiliere = new UniversitaireSonFiliere();
        $sonFiliereForm = $this->createForm(SonFiliereType::class, $sonFiliere);

        if($request->getMethod() == 'POST') {
            $personForm->handleRequest($request);
            $infoEtudiantForm->handleRequest($request);
            $universitaireForm->handleRequest($request);
            $sonFiliereForm->handleRequest($request);

            $universitaire->setPerson($person);
            $universitaire->setInfoEtudiant($infoEtudiant);

            $em = $this->getDoctrine()->getManager();
            $em->persist($universitaire);

            $sonFiliere->setUniversitaire($universitaire);
            $em->persist($sonFiliere);

            $em->flush();

            return $this->redirect($this->generateUrl('accueil_universite'));

        }
        return $this->render('MascaEtudiantBundle:Universite:inscription.html.twig',array(
            'personForm'=>$personForm->createView(),
            'infoEtudiantForm'=>$infoEtudiantForm->createView(),
            'etudeForm'=>$sonFiliereForm->createView(),
            'universitaireForm'=>$universitaireForm->createView()
        ));
    }

    /**
     * @param Request $request
     * @param Universitaire $universitaire
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/details/{id}", name="details_universite")
     */
    public function detailsAction(Request $request, Universitaire $universitaire) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_USER')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        /**
         * @var $sesFilieres UniversitaireSonFiliere[]
         */
        $sesFilieres = $this->getDoctrine()->getManager()
                            ->getRepository('MascaEtudiantBundle:UniversitaireSonFiliere')->findByUniversitaire($universitaire);
        return $this->render('MascaEtudiantBundle:Universite:details.html.twig', array(
            'universitaire'=>$universitaire,
            'sesFilieres'=>$sesFilieres
        ));
    }

    /**
     * @param Request $request
     * @param Universitaire $universitaire
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/modifier/{id}", name="modifier_universite")
     */
    public function modifierAction(Request $request, Universitaire $universitaire) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_SECRETAIRE')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $personForm = $this->createForm(PersonType::class, $universitaire->getPerson());
        $infoEtudiantForm = $this->createForm(InfoEtudiantType::class, $universitaire->getInfoEtudiant());

        if($request->getMethod() == 'POST') {
            $personForm->handleRequest($request);
            $infoEtudiantForm->handleRequest($request);

            $this->getDoctrine()->getManager()->flush();
            return $this->redirect($this->generateUrl('details_universite',array('id'=>$universitaire->getId())));

        }

        return $this->render('MascaEtudiantBundle:Universite:modifier.html.twig',array(
            'personForm'=>$personForm->createView(),
            'infoEtudiantForm'=>$infoEtudiantForm->createView(),
            'universiteId'=>$universitaire->getId()
        ));
    }


}