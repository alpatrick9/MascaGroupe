<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 7/20/16
 * Time: 8:40 AM
 */

namespace Masca\EtudiantBundle\Controller\Lycee\Impression;


use Masca\EtudiantBundle\Entity\Classe;
use Masca\EtudiantBundle\Entity\DatePayementEcolageLycee;
use Masca\EtudiantBundle\Entity\EmploiDuTempsLycee;
use Masca\EtudiantBundle\Entity\FraisScolariteLyceen;
use Masca\EtudiantBundle\Entity\GrilleFraisScolariteLycee;
use Masca\EtudiantBundle\Entity\Lyceen;
use Masca\EtudiantBundle\Entity\LyceenNote;
use Masca\EtudiantBundle\Entity\LyceenRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LyceeImpressionController extends Controller
{

    /**
     * @param Request $request
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listLyceePrintAction(Request $request, $page) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_USER_L')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $nbParPage = $this->getParameter('nbparpage');
        /**
         * @var $repository LyceenRepository
         */
        $repository = $this->getDoctrine()->getManager()
            ->getRepository('MascaEtudiantBundle:Lyceen');
        /**
         * @var $lyceens Lyceen[]
         */
        $lyceens = $repository->getLyceens($nbParPage,$page);
        
        return $this->render('MascaEtudiantBundle:Impression/Lycee:list-lyceen.html.twig',array(
            'lyceens'=>$lyceens,
            'page'=> $page,
            'nbPage' => ceil(count($lyceens)/$nbParPage)
        ));
    }

    public function noteLyceePrintAction(Request $request, Lyceen $lyceen) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_SG_L')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $totalCoef = 0;

        $totalTrimestre1 = 0;
        $totalTrimestre2 = 0;
        $totalTrimestre3 = 0;

        $moyenneTrimestre1 = 0;
        $moyenneTrimestre2 = 0;
        $moyenneTrimestre3 = 0;

        /**
         * @var $notes LyceenNote[]
         */
        $notes = $this->getDoctrine()->getManager()->getRepository("MascaEtudiantBundle:LyceenNote")->findByLyceen($lyceen);
        foreach($notes as $note) {
            $totalCoef = $totalCoef + $note->getCoefficient();

            $totalTrimestre1 = $totalTrimestre1 + $note->getCoefficient()*$note->getNoteTrimestre1();
            $totalTrimestre2 = $totalTrimestre2 + $note->getCoefficient()*$note->getNoteTrimestre2();
            $totalTrimestre3 = $totalTrimestre3 + $note->getCoefficient()*$note->getNoteTrimestre3();

        }

        if($totalCoef != 0) {
            $moyenneTrimestre1 = $totalTrimestre1 / $totalCoef;
            $moyenneTrimestre2 = $totalTrimestre2 / $totalCoef;
            $moyenneTrimestre3 = $totalTrimestre3 / $totalCoef;
        }

        $moyenGeneral = $moyenneTrimestre1;
        if($moyenneTrimestre2 !=0 ) {
            $moyenGeneral = ($moyenneTrimestre1 + $moyenneTrimestre2)/2;
        }
        if($moyenneTrimestre2 != 0 && $moyenneTrimestre3 != 0) {
            $moyenGeneral = ($moyenneTrimestre1 + $moyenneTrimestre2 + $moyenneTrimestre3)/3;
        }

        return $this->render('MascaEtudiantBundle:Impression/Lycee:note.html.twig',[
            'notes'=>$notes,
            'lyceen'=>$lyceen,
            'totalCoef'=>$totalCoef,
            'totalTrimestre1'=>$totalTrimestre1,
            'totalTrimestre2'=>$totalTrimestre2,
            'totalTrimestre3'=>$totalTrimestre3,
            'moyenne1'=>$moyenneTrimestre1,
            'moyenne2'=>$moyenneTrimestre2,
            'moyenne3'=>$moyenneTrimestre3,
            'moyenneGeneral'=>$moyenGeneral,
            'date'=>new \DateTime()
        ]);
    }

    public function ecolageLyceePrintAction(Request $request, Lyceen $lyceen) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_ECO_L')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }

        /**
         * @var $motantEcolage GrilleFraisScolariteLycee
         */
        $motantEcolage = $this->getDoctrine()->getManager()
            ->getRepository('MascaEtudiantBundle:GrilleFraisScolariteLycee')->findOneByClasse($lyceen->getSonClasse());

        if(empty($motantEcolage)) {
            return $this->render("::message-layout.html.twig",[
                'message'=>'Veuillez contacter le responsabe car il n\'y a pas un montant d\'écolage attribué à ce classe: '.$lyceen->getSonClasse()->getIntitule(),
                'previousLink'=>$request->headers->get('referer')
            ]);
        }

        /**
         * @var $statusEcolages FraisScolariteLyceen[]
         */
        $statusEcolages = $this->getDoctrine()->getManager()
            ->getRepository('MascaEtudiantBundle:FraisScolariteLyceen')
            ->findBy(
                array(
                    'lyceen'=>$lyceen,
                    'anneeScolaire'=>$lyceen->getAnneeScolaire()
                ));

        /**
         * @var $datePayements DatePayementEcolageLycee[]
         */
        $datePayements = [];

        foreach ($statusEcolages as $ecolage) {
            $datePayements[$ecolage->getId()] = $this->getDoctrine()->getManager()
                ->getRepository('MascaEtudiantBundle:DatePayementEcolageLycee')->findByFraisScolariteLyceen($ecolage);
        }

        return $this->render('MascaEtudiantBundle:Impression/Lycee:ecolage.html.twig', array(
            'lyceen'=>$lyceen,
            'statusEcolages'=> $statusEcolages,
            'montant'=>$motantEcolage->getMontant(),
            'datesPayement'=>$datePayements
        ));
    }
    
    public function emploiDuTempsPrintAction(Request $request, Classe $classe) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_SG_L')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
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
        return $this->render('MascaEtudiantBundle:Impression/Lycee:emploidutemps.html.twig', array(
            'jours'=>$jours,
            'heures'=>$heures,
            'nbJours'=> sizeof($jours),
            'nbHeures'=> sizeof($heures),
            'matieres'=> $matieres,
            'classe'=>$classe
        ));
    }

}