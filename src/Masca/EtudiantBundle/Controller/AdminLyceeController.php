<?php
/**
 * Created by PhpStorm.
 * User: patmi
 * Date: 27/04/2016
 * Time: 08:59
 */

namespace Masca\EtudiantBundle\Controller;


use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Masca\EtudiantBundle\Entity\Classe;
use Masca\EtudiantBundle\Entity\ClasseRepository;
use Masca\EtudiantBundle\Entity\EmploiDuTempsLycee;
use Masca\EtudiantBundle\Entity\GrilleFraisScolariteLycee;
use Masca\EtudiantBundle\Entity\GrilleFraisScolariteLyceeRepository;
use Masca\EtudiantBundle\Entity\MatiereLycee;
use Masca\EtudiantBundle\Repository\MatiereLyceeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AdminLyceeController extends Controller
{
    /**
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/lycee/admin/{page}", name="admin_lycee_accueil", defaults={"page" = 1})
     */
    public function indexAction($page) {
        $nbParPage = 30;
        /**
         * @var $repository ClasseRepository
         */
        $repository = $this->getDoctrine()->getManager()
            ->getRepository('MascaEtudiantBundle:Classe');
        /**
         * @var $classes Classe[]
         */
        $classes = $repository->getClasses($nbParPage,$page);

        return $this->render('MascaEtudiantBundle:Admin_lycee:index.html.twig',array(
            'classes'=>$classes,
            'page'=> $page,
            'nbPage' => ceil(count($classes)/$nbParPage)));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/lycee/admin/classe/ajoute/", name="ajouter_classe_lycee")
     */
    public function creerClasseAction(Request $request) {
            $classe = new Classe();
            $classeFormBuilder = $this->createFormBuilder($classe);
            $classeFormBuilder
                ->add('intitule',TextType::class,array(
                    'label'=> 'Nom du classe',
                    'attr'=>array('placeholder'=>'2nd B')
                ))
                ->add('niveauEtude',EntityType::class,array(
                    'label'=>'Niveau d\'étude',
                    'class'=>'Masca\EtudiantBundle\Entity\NiveauEtude',
                    'choice_label'=>'intitule',
                    'placeholder'=>"choisissez...",
                    'empty_data'=>null
                ));
            $classeForm = $classeFormBuilder->getForm();

            if($request->getMethod() == 'POST') {
                $classeForm->handleRequest($request);
                if($this->getDoctrine()->getManager()->getRepository('MascaEtudiantBundle:Classe')->findOneBy([
                        'intitule'=>$classe->getIntitule()
                    ]) != null){
                    return $this->render('MascaEtudiantBundle:Admin_lycee:creer-classe.html.twig', array(
                        'classeForm'=>$classeForm->createView(),
                        'error_message'=>'La classe '.$classe->getIntitule().' existe déjà, choisissez une autre'
                    ));
                }

                $em = $this->getDoctrine()->getManager();
                $em->persist($classe);
                $em->flush();
                return $this->redirect($this->generateUrl('admin_lycee_accueil'));
            }

        return $this->render('MascaEtudiantBundle:Admin_lycee:creer-classe.html.twig', array(
            'classeForm'=>$classeForm->createView()
        ));
    }

    /**
     * @param Request $request
     * @param Classe $classe
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @ParamConverter("classe", options={"mapping": {"classe_id":"id"}})
     * @Route("/lycee/admin/modifier/classe/{classe_id}", name="modifier_classe_lycee")
     */
    public function modiferClasseAction(Request $request, Classe $classe) {
        $classeFormBuilder = $this->createFormBuilder($classe);
        $classeFormBuilder
            ->add('intitule',TextType::class,array(
                'label'=> 'Nom du classe',
                'attr'=>array('placeholder'=>'2nd B'),
                'data'=>$classe->getIntitule()
            ))
            ->add('niveauEtude',EntityType::class,array(
                'label'=>'Niveau d\'étude',
                'class'=>'Masca\EtudiantBundle\Entity\NiveauEtude',
                'choice_label'=>'intitule',
                'placeholder'=>"choisissez...",
                'data'=>$classe->getNiveauEtude(),
                'attr'=>['readonly'=>true]
            ));
        $classeForm = $classeFormBuilder->getForm();

        if($request->getMethod() == 'POST') {
            $classeForm->handleRequest($request);
            if($this->getDoctrine()->getManager()->getRepository('MascaEtudiantBundle:Classe')->findOneBy([
                    'intitule'=>$classe->getIntitule()
                ]) != null){
                return $this->render('MascaEtudiantBundle:Admin_lycee:creer-classe.html.twig', array(
                    'classeForm'=>$classeForm->createView(),
                    'error_message'=>'La classe '.$classe->getIntitule().' existe déjà ou vous n\'avez pas fais de changement, choisissez une autre nom ou Annuler'
                ));
            }

            $this->getDoctrine()->getManager()->flush();
            return $this->redirect($this->generateUrl('admin_lycee_accueil'));
        }

        return $this->render('MascaEtudiantBundle:Admin_lycee:creer-classe.html.twig', array(
            'classeForm'=>$classeForm->createView()
        ));
    }

    /**
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/lycee/admin/grille/ecolage/{page}", name="grille_ecolage_lycee", defaults={"page" = 1})
     */
    public function grilleEcolageAction($page) {
        $nbParPage = 30;
        /**
         * @var $repository GrilleFraisScolariteLyceeRepository
         */
        $repository = $this->getDoctrine()->getManager()
            ->getRepository('MascaEtudiantBundle:GrilleFraisScolariteLycee');
        /**
         * @var $grilles GrilleFraisScolariteLycee[]
         */
        $grilles = $repository->getGrilles($nbParPage,$page);

        return $this->render('MascaEtudiantBundle:Admin_lycee:grilles-frais-scolarite.html.twig',array(
            'grilles'=>$grilles,
            'page'=> $page,
            'nbPage' => ceil(count($grilles)/$nbParPage)));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/lycee/admin/ajouter-grille-ecolage/", name="ajouter_grille_ecolage_lycee")
     */
    public function creerGrilleEcolageAction(Request $request) {
        $grille = new GrilleFraisScolariteLycee();
        $grilleFormBuilder = $this->createFormBuilder($grille);
        $grilleFormBuilder
            ->add('classe',EntityType::class,array(
                'label'=>'Classe correspondant',
                'class'=>'Masca\EtudiantBundle\Entity\Classe',
                'choice_label'=>'intitule',
                'placeholder'=>'Choisissez..'
            ))
            ->add('montant',NumberType::class,array(
                'label'=>'Montant en (Ar)'
            ));
        $grilleForm = $grilleFormBuilder->getForm();

        if($request->getMethod() == 'POST') {
            $grilleForm->handleRequest($request);
            if($this->getDoctrine()->getManager()->getRepository('MascaEtudiantBundle:GrilleFraisScolariteLycee')
                ->findOneBy(['classe'=>$grille->getClasse()]) != null) {
                return $this->render('MascaEtudiantBundle:Admin_lycee:creer-grille-ecolage.html.twig', array(
                    'grilleForm'=>$grilleForm->createView(),
                    'error_message'=>'La grille de frais de scolarite de la classe '.$grille->getClasse()->getIntitule().' existe déjà, choisissez une autre classe'
                ));
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($grille);
            $em->flush();
            return $this->redirect($this->generateUrl('grille_ecolage_lycee'));
        }
        return $this->render('MascaEtudiantBundle:Admin_lycee:creer-grille-ecolage.html.twig', array(
            'grilleForm'=>$grilleForm->createView()
        ));
    }

    /**
     * @param Request $request
     * @param GrilleFraisScolariteLycee $grille
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @ParamConverter("grille", options={"mapping": {"grille_id": "id"}})
     * @Route("/lycce/admin/modifier-grille-ecolage/{grille_id}", name="modifier_grille_ecolage_lycee")
     */
    public function modifierGrilleEcolageAction(Request $request, GrilleFraisScolariteLycee $grille) {
        $grilleFormBuilder = $this->createFormBuilder($grille);
        $grilleFormBuilder
            ->add('classe',EntityType::class,array(
                'label'=>'Classe correspondant',
                'class'=>'Masca\EtudiantBundle\Entity\Classe',
                'choice_label'=>'intitule',
                'placeholder'=>'Choisissez..',
                'data'=>$grille->getClasse(),
                'attr'=>['readonly'=>true]
            ))
            ->add('montant',NumberType::class,array(
                'label'=>'Montant en (Ar)',
                'data'=>$grille->getMontant()
            ));
        $grilleForm = $grilleFormBuilder->getForm();

        if($request->getMethod() == 'POST') {
            $grilleForm->handleRequest($request);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirect($this->generateUrl('grille_ecolage_lycee'));
        }
        return $this->render('MascaEtudiantBundle:Admin_lycee:creer-grille-ecolage.html.twig', array(
            'grilleForm'=>$grilleForm->createView()
        ));
    }

    /**
     * @param Classe $classe
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/lycee/admin/emploi-du-temps/{id}", name="emploi_du_temps_lycee")
     */
    public function emploiDuTempsAction(Classe $classe) {
        $jours = $this->getParameter('jours');
        $heures = $this->getParameter('heures');
        /**
         * @var $matieres EmploiDuTempsLycee[][]
         */
        $matieres = array();
        for($j=0 ;$j<sizeof($jours);$j++) {
            $matieresJournalier = array();
            for($h=0 ;$h<sizeof($heures);$h++) {
                $matiereTemp = $this->getDoctrine()->getManager()
                    ->getRepository('MascaEtudiantBundle:EmploiDuTempsLycee')->getMatiereBy($classe,$j,$h);
                if($matiereTemp != null) {
                    array_push($matieresJournalier,$matiereTemp);
                }
                else {
                    array_push($matieresJournalier,'');
                }
            }
            array_push($matieres,$matieresJournalier);
        }
        return $this->render('MascaEtudiantBundle:Admin_lycee:emploidutemps.html.twig', array(
            'jours'=>$jours,
            'heures'=>$heures,
            'nbJours'=> sizeof($jours),
            'nbHeures'=> sizeof($heures),
            'matieres'=> $matieres,
            'classe'=>$classe
        ));
    }

    /**
     * @param Request $request
     * @param Classe $classe
     * @param $jourIndex
     * @param $heureIndex
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @ParamConverter("classe", options={"mapping": {"classe_id":"id"}})
     * @Route("/lycee/admin/emploi-du-temps/ajouter-matiere/{classe_id}/{jourIndex}/{heureIndex}", name="ajouter_matiere_emplois_du_temps_lycee")
     */
    public function ajouterMatiereEmploiDuTempsAction(Request $request, Classe $classe,$jourIndex, $heureIndex) {
        $jours = $this->getParameter('jours');
        $heures = $this->getParameter('heures');

        $emploiDuTemps = new EmploiDuTempsLycee();
        $emploiDuTempsFormBuilder = $this->createFormBuilder($emploiDuTemps);
        $emploiDuTempsFormBuilder
            ->add('classe',EntityType::class,array(
                'label'=>'Classe',
                'class'=>'Masca\EtudiantBundle\Entity\Classe',
                'choice_label'=>'intitule',
                'data'=>$classe,
                'disabled'=>true
            ))
            ->add('matiere',EntityType::class,array(
                'label'=>'Matiere',
                'class'=>'Masca\EtudiantBundle\Entity\MatiereLycee',
                'choice_label'=>'intitule',
                'placeholder'=>'choisissez...'
            ));
        $emploiDuTempsForm = $emploiDuTempsFormBuilder->getForm();

        if($request->getMethod() == 'POST') {
            $emploiDuTempsForm->handleRequest($request);
            $emploiDuTemps->setHeureIndex($heureIndex);
            $emploiDuTemps->setJourIndex($jourIndex);
            $emploiDuTemps->setClasse($classe);
            $em = $this->getDoctrine()->getManager();
            $em->persist($emploiDuTemps);
            $em->flush();
            return $this->redirect($this->generateUrl('emploi_du_temps_lycee',array('id'=>$classe->getId())));
        }

        return $this->render('MascaEtudiantBundle:Admin_lycee:ajouter-matiere-emploidutemps.html.twig', array(
            'emploiDuTempsForm'=>$emploiDuTempsForm->createView(),
            'jour'=>$jours[$jourIndex],
            'heure'=>$heures[$heureIndex],
            'classeId'=>$classe->getId()
        ));
    }

    /**
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/lycee/admin/liste/matiere/{page}", name="liste_matieres_lycee", defaults={"page"=1})
     */
    public function listMatieresAction($page) {
        $nbParPage = 30;
        /**
         * @var $repository MatiereLyceeRepository
         */
        $repository = $this->getDoctrine()->getManager()
            ->getRepository('MascaEtudiantBundle:MatiereLycee');
        /**
         * @var $matieres MatiereLycee[]
         */
        $matieres = $repository->getMatieres($nbParPage,$page);

        return $this->render('MascaEtudiantBundle:Admin_lycee:list-matiere.html.twig',array(
            'matieres'=>$matieres,
            'page'=> $page,
            'nbPage' => ceil(count($matieres)/$nbParPage)
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/lycee/admin/ajouter/matiere", name="ajouter_matiere_lycee")
     */
    public function ajouterMatiereAction(Request $request) {
        $matiere = new MatiereLycee();
        $matiereFormBuilder = $this->createFormBuilder($matiere);
        $matiereFormBuilder->add('intitule',TextType::class, array(
            'label'=>'Nom du matière'
        ));

        $matiereForm = $matiereFormBuilder->getForm();
        if($request->getMethod() == 'POST') {
            $matiereForm->handleRequest($request);
            if($this->getDoctrine()->getManager()->getRepository('MascaEtudiantBundle:MatiereLycee')
                ->findOneBy(['intitule'=>$matiere->getIntitule()]) != null) {
                return $this->render('MascaEtudiantBundle:Admin_lycee:ajouter-matiere.html.twig', array(
                    'matiereForm'=>$matiereForm->createView(),
                    'error_message'=>'La matière '.$matiere->getIntitule().' existe déjà, choisissez un autre nom'
                ));
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($matiere);
            $em->flush();
            return $this->redirect($this->generateUrl('liste_matieres_lycee'));
        }
        return $this->render('MascaEtudiantBundle:Admin_lycee:ajouter-matiere.html.twig', array(
            'matiereForm'=>$matiereForm->createView()
        ));
    }

    /**
     * @param Request $request
     * @param MatiereLycee $matiere
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @ParamConverter("matiere", options={"mapping": {"matiere_id":"id"}})
     * @Route("/lycee/admin/modifier/matiere/{matiere_id}", name="modifier_matiere_lycee")
     */
    public function modifierMatiereAction(Request $request, MatiereLycee $matiere) {
        $matiereFormBuilder = $this->createFormBuilder($matiere);
        $matiereFormBuilder->add('intitule',TextType::class, array(
            'label'=>'Nom du matière',
            'data'=>$matiere->getIntitule()
        ));

        $matiereForm = $matiereFormBuilder->getForm();
        if($request->getMethod() == 'POST') {
            $matiereForm->handleRequest($request);
            if($this->getDoctrine()->getManager()->getRepository('MascaEtudiantBundle:MatiereLycee')
                    ->findOneBy(['intitule'=>$matiere->getIntitule()]) != null) {
                return $this->render('MascaEtudiantBundle:Admin_lycee:ajouter-matiere.html.twig', array(
                    'matiereForm'=>$matiereForm->createView(),
                    'error_message'=>'La matière '.$matiere->getIntitule().' existe déjà ou vous n\'avez pas fait de modification, choisissez un autre nom ou Annuler'
                ));
            }
            $this->getDoctrine()->getManager()->flush();
            return $this->redirect($this->generateUrl('liste_matieres_lycee'));
        }
        return $this->render('MascaEtudiantBundle:Admin_lycee:ajouter-matiere.html.twig', array(
            'matiereForm'=>$matiereForm->createView()
        ));
    }
}