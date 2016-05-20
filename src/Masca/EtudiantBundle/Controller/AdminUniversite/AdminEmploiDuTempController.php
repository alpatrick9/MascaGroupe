<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/20/16
 * Time: 3:49 PM
 */

namespace Masca\EtudiantBundle\Controller\AdminUniversite;


use Masca\EtudiantBundle\Entity\EmploiDuTempsUniv;
use Masca\EtudiantBundle\Entity\FiliereParNiveau;
use Masca\EtudiantBundle\Type\EmploiDuTempsUnivType;
use Masca\EtudiantBundle\Type\FiliereParNiveauType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class AdminEmploiDuTempController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/universite/admin/emploi-du-temps/", name="emploi_du_temps_univerite")
     */
    public function indexAction(){
        /**
         * @var $filierParNiveau FiliereParNiveau[]
         */
        $filierParNiveau =$this->getDoctrine()->getManager()->getRepository('MascaEtudiantBundle:FiliereParNiveau')->findAll();
        return $this->render('MascaEtudiantBundle:Admin_universite:list-filiere-par-niveau.html.twig',[
           'filiereParNiveaux'=> $filierParNiveau
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/universite/admin/emploi-du-temps/creer/", name="creer_emploi_du_temps_universite")
     */
    public function creerEmploiDuTemps(Request $request) {
        $filierParNiveau = new FiliereParNiveau();
        $form = $this->createForm(FiliereParNiveauType::class, $filierParNiveau);

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $em = $this->getDoctrine()->getManager();
            $em->persist($filierParNiveau);
            $em->flush();
            return $this->redirect($this->generateUrl('emploi_du_temps_univerite'));
        }
        return $this->render('MascaEtudiantBundle:Admin_universite:formulaire-filiere-par-niveau.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @param FiliereParNiveau $filierParNiveau
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/universite/admin/emploi-du-temps/voir/{filiereParNiveau_id}", name="voir_emploi_du_temps_universite")
     * @ParamConverter("filierParNiveau", options={"mapping": {"filiereParNiveau_id":"id"}})
     */
    public function voirEmploiDuTempsAction(FiliereParNiveau $filierParNiveau) {
        $jours = $this->getParameter('jours');
        $heures = $this->getParameter('heures');
        /**
         * @var $matieres EmploiDuTempsUniv[][]
         */
        $matieres = array();
        for($j=0 ;$j<sizeof($jours);$j++) {
            $matieresJournalier = array();
            for($h=0 ;$h<sizeof($heures);$h++) {
                $matiereTemp = $this->getDoctrine()->getManager()
                    ->getRepository('MascaEtudiantBundle:EmploiDuTempsUniv')->getMatiereBy($filierParNiveau,$j,$h);
                if($matiereTemp != null) {
                    array_push($matieresJournalier,$matiereTemp);
                }
                else {
                    array_push($matieresJournalier,'');
                }
            }
            array_push($matieres,$matieresJournalier);
        }
        return $this->render('MascaEtudiantBundle:Admin_universite:emploidutemps.html.twig', array(
            'jours'=>$jours,
            'heures'=>$heures,
            'nbJours'=> sizeof($jours),
            'nbHeures'=> sizeof($heures),
            'matieres'=> $matieres,
            'filiere'=>$filierParNiveau
        ));
    }

    /**
     * @param Request $request
     * @param FiliereParNiveau $filiereParNiveau
     * @param $jourIndex
     * @param $heureIndex
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @ParamConverter("filiereParNiveau", options={"mapping": {"filiereParNiveau_id":"id"}})
     * @Route("/universite/admin/emploi-du-temps/ajouter-matiere/{filiereParNiveau_id}/{jourIndex}/{heureIndex}", name="ajouter_emplois_du_temps_universite")
     */
    public function ajouterMatiereAction(Request $request, FiliereParNiveau $filiereParNiveau,$jourIndex, $heureIndex) {
        $jours = $this->getParameter('jours');
        $heures = $this->getParameter('heures');

        $emploiDuTemps = new EmploiDuTempsUniv();
        $emploiDuTempsForm = $this->createForm(EmploiDuTempsUnivType::class,$emploiDuTemps);

        if($request->getMethod() == 'POST') {
            $emploiDuTempsForm->handleRequest($request);

            $emploiDuTemps->setHeureIndex($heureIndex);
            $emploiDuTemps->setJourIndex($jourIndex);
            $emploiDuTemps->setFiliereParNiveau($filiereParNiveau);
            $em = $this->getDoctrine()->getManager();
            $em->persist($emploiDuTemps);
            $em->flush();
            return $this->redirect($this->generateUrl('voir_emploi_du_temps_universite',array('filiereParNiveau_id'=>$filiereParNiveau->getId())));
        }

        return $this->render('MascaEtudiantBundle:Admin_universite:formulaire-emploidutemps-universite.html.twig', array(
            'form'=>$emploiDuTempsForm->createView(),
            'jour'=>$jours[$jourIndex],
            'heure'=>$heures[$heureIndex],
            'filiere'=>$filiereParNiveau
        ));
    }

    /**
     * @param Request $request
     * @param EmploiDuTempsUniv $emploiDuTempsUniv
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @ParamConverter("emploiDuTempsUniv", options={"mapping": {"emploiDuTempsUniv_id":"id"}})
     * @Route("/universite/admin/emploi-du-temps/modifier-matiere/{emploiDuTempsUniv_id}", name="modifier_emplois_du_temps_universite")
     */
    public function modifierMatiereAction(Request $request, EmploiDuTempsUniv $emploiDuTempsUniv) {
        $jours = $this->getParameter('jours');
        $heures = $this->getParameter('heures');

        $emploiDuTempsForm = $this->createForm(EmploiDuTempsUnivType::class,$emploiDuTempsUniv);

        if($request->getMethod() == 'POST') {
            $emploiDuTempsForm->handleRequest($request);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirect($this->generateUrl('voir_emploi_du_temps_universite',array('filiereParNiveau_id'=>$emploiDuTempsUniv->getFiliereParNiveau()->getId())));
        }


        return $this->render('MascaEtudiantBundle:Admin_universite:formulaire-emploidutemps-universite.html.twig', array(
            'form'=>$emploiDuTempsForm->createView(),
            'jour'=>$jours[$emploiDuTempsUniv->getJourIndex()],
            'heure'=>$heures[$emploiDuTempsUniv->getHeureIndex()],
            'filiere'=>$emploiDuTempsUniv->getFiliereParNiveau()
        ));
    }

    /**
     * @param EmploiDuTempsUniv $emploiDuTempsUniv
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @ParamConverter("emploiDuTempsUniv", options={"mapping": {"emploiDuTempsUniv_id":"id"}})
     * @Route("/universite/admin/emploi-du-temps/supprimer-matiere/{emploiDuTempsUniv_id}", name="supprimer_emplois_du_temps_universite")
     */
    public function supprimerMatierAction(EmploiDuTempsUniv $emploiDuTempsUniv) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($emploiDuTempsUniv);
        $em->flush();
        return $this->redirect($this->generateUrl('voir_emploi_du_temps_universite',array('filiereParNiveau_id'=>$emploiDuTempsUniv->getFiliereParNiveau()->getId())));
    }
}