<?php
/**
 * Created by PhpStorm.
 * User: patmi
 * Date: 06/05/2016
 * Time: 10:17
 */

namespace Masca\EtudiantBundle\Controller;


use Masca\EtudiantBundle\Entity\Filiere;
use Masca\EtudiantBundle\Entity\FiliereRepository;
use Masca\EtudiantBundle\Entity\GrilleFraisScolariteUniversite;
use Masca\EtudiantBundle\Entity\NiveauEtude;
use Masca\EtudiantBundle\Entity\Semestre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AdminUniversiteController extends Controller
{
    /**
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/universite/admin/{page}", name="admin_univ_filiere", defaults={"page"=1})
     */
    public function indexAction($page) {
        $nbParPage = 30;
        /**
         * @var $repository FiliereRepository
         */
        $repository = $this->getDoctrine()->getManager()
            ->getRepository('MascaEtudiantBundle:Filiere');
        /**
         * @var $filieres Filiere[]
         */
        $filieres = $repository->getFiliers($nbParPage,$page);

        return $this->render('MascaEtudiantBundle:Admin_universite:index.html.twig',array(
            'filieres'=>$filieres,
            'page'=> $page,
            'nbPage' => ceil(count($filieres)/$nbParPage)));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/universite/admin/ajoute-filiere/", name="ajouter_filiere_univ")
     */
    public function ajouterFiliereAction(Request $request) {
        $filiere = new Filiere();

        $filiereFormBuilder = $this->createFormBuilder($filiere);
        $filiereFormBuilder->add('intitule',TextType::class,[
           'label'=>'Intitulé du filière'
        ]);

        $filiereForm = $filiereFormBuilder->getForm();

        if($request->getMethod() == 'POST') {
            $filiereForm->handleRequest($request);
            $em = $this->getDoctrine()->getManager();
            $em->persist($filiere);
            $em->flush();
            return $this->redirect($this->generateUrl('admin_univ_filiere'));
        }
        return $this->render('MascaEtudiantBundle:Admin_universite:ajouter-filiere.html.twig',[
            'filiereForm'=>$filiereForm->createView()
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/universite/admin/niveau-etude/", name="niveau_etude_univ")
     */
    public function niveauEtudeAction() {
        return $this->render('MascaEtudiantBundle:Admin_universite:niveau-etude.html.twig',[
            'niveauEtudes'=> $this->getDoctrine()->getManager()
                                    ->getRepository('MascaEtudiantBundle:NiveauEtude')->findAll()
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/universite/admin/ajoute-niveau-etude/", name="ajouter_niveau_etude_univ")
     */
    public function ajouterNiveauEtudeAction(Request $request) {
        $niveau = new NiveauEtude();
        $formBuilder = $this->createFormBuilder($niveau);
        $formBuilder->add('intitule',TextType::class,[
            'label'=>'Intitulé du niveau'
        ]);
        $form = $formBuilder->getForm();

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $em = $this->getDoctrine()->getManager();
            $em->persist($niveau);
            $em->flush();
            return $this->redirect($this->generateUrl('niveau_etude_univ'));
        }
        return $this->render('MascaEtudiantBundle:Admin_universite:ajouter-niveau-etude.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/universite/admin/semestre/", name="semestre_univ")
     */
    public function semestreAction() {
        return $this->render('MascaEtudiantBundle:Admin_universite:semestre.html.twig',[
            'semestres'=>$this->getDoctrine()->getManager()
                            ->getRepository('MascaEtudiantBundle:Semestre')->findAll()
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/universite/admin/ajoute-semestre/", name="ajouter_semestre_univ")
     */
    public function ajouterSemestreAction(Request $request) {
        $semestre = new Semestre();
        $formBuilder = $this->createFormBuilder($semestre);
        $formBuilder->add('intitule',TextType::class,[
            'label'=>'Intituler du semestre',
            'attr'=>['placeholder'=>'Semestre 1']
        ]);
        $form = $formBuilder->getForm();

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $em = $this->getDoctrine()->getManager();
            $em->persist($semestre);
            $em->flush();
            return $this->redirect($this->generateUrl('semestre_univ'));
        }
        return $this->render('MascaEtudiantBundle:Admin_universite:ajouter-semestre.html.twig',[
           'form'=>$form->createView()
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/universite/admin/grille-ecolage/", name="grille_ecolage_universite")
     */
    public function grilleFraisScolariteAction() {
        return $this->render('MascaEtudiantBundle:Admin_universite:grilles-frais-scolarite.html.twig',[
           'grilles'=>$this->getDoctrine()->getManager()->getRepository('MascaEtudiantBundle:GrilleFraisScolariteUniversite')->findAll()
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/universite/admin/ajouter-grille-ecolage/", name="ajouter_grille_ecolage_universite")
     */
    public function ajouterGrilleAction(Request $request) {
        $grille = new GrilleFraisScolariteUniversite();
        $formBuilder = $this->createFormBuilder($grille);
        $formBuilder
            ->add('filiere',EntityType::class,[
                'label'=>'Filiere',
                'class'=>'Masca\EtudiantBundle\Entity\Filiere',
                'choice_label'=>'intitule',
                'placeholder'=>'Choisissez'
            ])
            ->add('niveauEtude',EntityType::class,[
                'label'=>'Niveau d\étude',
                'class'=>'Masca\EtudiantBundle\Entity\NiveauEtude',
                'choice_label'=>'intitule',
                'placeholder'=>'Choisissez'
            ])
            ->add('montant',NumberType::class,[
                'label'=>'Montant (Ar)'
            ]);
        $form = $formBuilder->getForm();
        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if(!$this->getDoctrine()->getManager()->
                getRepository('MascaEtudiantBundle:GrilleFraisScolariteUniversite')->isValid($grille->getFiliere(),$grille->getNiveauEtude())) {
                $js = '<script  type="text/javascript">'.
                    'document.getElementById("DivInfo").style.display = "block";'.
                    '</script>';
                return $this->render('MascaEtudiantBundle:Admin_universite:ajouter-grilles-frais-scolarite.html.twig', [
                    'form'=>$form->createView(),
                    'js'=>$js
                ]);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($grille);
            $em->flush();
            return $this->redirect($this->generateUrl('grille_ecolage_universite'));
        }
        return $this->render('MascaEtudiantBundle:Admin_universite:ajouter-grilles-frais-scolarite.html.twig',[
            'form'=>$form->createView()
        ]);
    }

}