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
use Masca\EtudiantBundle\Type\ClasseType;
use Masca\EtudiantBundle\Type\EmploiDuTempsLyceeType;
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
            
            $classeForm = $this->createForm(ClasseType::class, $classe);

            if($request->getMethod() == 'POST') {
                $classeForm->handleRequest($request);
                if($this->getDoctrine()->getManager()->getRepository('MascaEtudiantBundle:Classe')->findOneBy([
                        'intitule'=>$classe->getIntitule()
                    ]) != null){
                    return $this->render('MascaEtudiantBundle:Admin_lycee:formulaire-classe.html.twig', array(
                        'form'=>$classeForm->createView(),
                        'error_message'=>'La classe '.$classe->getIntitule().' existe déjà, choisissez une autre'
                    ));
                }

                $em = $this->getDoctrine()->getManager();
                $em->persist($classe);
                $em->flush();
                return $this->redirect($this->generateUrl('admin_lycee_classe'));
            }

        return $this->render('MascaEtudiantBundle:Admin_lycee:formulaire-classe.html.twig', array(
            'form'=>$classeForm->createView()
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
        $classeForm = $this->createForm(ClasseType::class, $classe);
        
        $niveauEtudeField = $classeForm->get('niveauEtude');
        $options = $niveauEtudeField->getConfig()->getOptions();
        $options['disabled'] = true;
        $classeForm->add('niveauEtude',EntityType::class,$options);

        if($request->getMethod() == 'POST') {
            $classeForm->handleRequest($request);
            if($this->getDoctrine()->getManager()->getRepository('MascaEtudiantBundle:Classe')->findOneBy([
                    'intitule'=>$classe->getIntitule()
                ]) != null){
                return $this->render('MascaEtudiantBundle:Admin_lycee:formulaire-classe.html.twig', array(
                    'form'=>$classeForm->createView(),
                    'error_message'=>'La classe '.$classe->getIntitule().' existe déjà ou vous n\'avez pas fais de changement, choisissez une autre nom ou Annuler'
                ));
            }

            $this->getDoctrine()->getManager()->flush();
            return $this->redirect($this->generateUrl('admin_lycee_classe'));
        }

        return $this->render('MascaEtudiantBundle:Admin_lycee:formulaire-classe.html.twig', array(
            'form'=>$classeForm->createView()
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
        $emploiDuTempsForm = $this->createForm(EmploiDuTempsLyceeType::class,$emploiDuTemps,[
            'trait_choices'=>[
                'classe'=>$classe
            ]
        ]);

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

        return $this->render('MascaEtudiantBundle:Admin_lycee:formulaire-matiere-emploidutemps.html.twig', array(
            'form'=>$emploiDuTempsForm->createView(),
            'jour'=>$jours[$jourIndex],
            'heure'=>$heures[$heureIndex],
            'classeId'=>$classe->getId()
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @ParamConverter("emploiDuTempsLycee", options={"mapping": {"emploiDuTempsLycee_id":"id"}})
     * @Route("/lycee/admin/emploi-du-temps/modifier-matiere/{emploiDuTempsLycee_id}", name="modifier_matiere_emplois_du_temps_lycee")
     */
    public function modifierMatiereEmploiDuTempsAction(Request $request, EmploiDuTempsLycee $emploiDuTempsLycee) {
        $jours = $this->getParameter('jours');
        $heures = $this->getParameter('heures');

        $emploiDuTempsForm = $this->createForm(EmploiDuTempsLyceeType::class,$emploiDuTempsLycee,[
            'trait_choices'=>[
                'classe'=>$emploiDuTempsLycee->getClasse()
            ]
        ]);

        if($request->getMethod() == 'POST') {
            $emploiDuTempsForm->handleRequest($request);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirect($this->generateUrl('emploi_du_temps_lycee',array('id'=>$emploiDuTempsLycee->getClasse()->getId())));
        }

        return $this->render('MascaEtudiantBundle:Admin_lycee:formulaire-matiere-emploidutemps.html.twig', array(
            'form'=>$emploiDuTempsForm->createView(),
            'jour'=>$jours[$emploiDuTempsLycee->getJourIndex()],
            'heure'=>$heures[$emploiDuTempsLycee->getHeureIndex()],
            'classeId'=>$emploiDuTempsLycee->getClasse()->getId()
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @ParamConverter("emploiDuTempsLycee", options={"mapping": {"emploiDuTempsLycee_id":"id"}})
     * @Route("/lycee/admin/emploi-du-temps/supprimer-matiere/{emploiDuTempsLycee_id}", name="supprimer_matiere_emplois_du_temps_lycee")
     */
    public function supprimerMatiereEmploiDuTempsAction(Request $request, EmploiDuTempsLycee $emploiDuTempsLycee) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($emploiDuTempsLycee);
        $em->flush();
        return $this->redirect($this->generateUrl('emploi_du_temps_lycee',array('id'=>$emploiDuTempsLycee->getClasse()->getId())));
    }

}