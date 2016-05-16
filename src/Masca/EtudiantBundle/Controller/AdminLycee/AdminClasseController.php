<?php
/**
 * Created by PhpStorm.
 * User: patmi
 * Date: 27/04/2016
 * Time: 08:59
 */

namespace Masca\EtudiantBundle\Controller\AdminLycee;

use Masca\EtudiantBundle\Entity\Classe;
use Masca\EtudiantBundle\Entity\ClasseRepository;
use Masca\EtudiantBundle\Entity\EmploiDuTempsLycee;
use Masca\EtudiantBundle\Entity\MatiereLycee;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AdminClasseController extends Controller
{
    /**
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/lycee/admin/{page}", name="admin_lycee_classe", defaults={"page" = 1})
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
                return $this->redirect($this->generateUrl('admin_lycee_classe'));
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
            return $this->redirect($this->generateUrl('admin_lycee_classe'));
        }

        return $this->render('MascaEtudiantBundle:Admin_lycee:creer-classe.html.twig', array(
            'classeForm'=>$classeForm->createView()
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
}