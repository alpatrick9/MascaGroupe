<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 7/20/16
 * Time: 9:11 AM
 */

namespace Masca\EtudiantBundle\Controller\Universite\Impression;


use Masca\EtudiantBundle\Entity\DatePayementEcolageUniv;
use Masca\EtudiantBundle\Entity\EmploiDuTempsUniv;
use Masca\EtudiantBundle\Entity\FiliereParNiveau;
use Masca\EtudiantBundle\Entity\FraisScolariteUniv;
use Masca\EtudiantBundle\Entity\GrilleFraisScolariteUniversite;
use Masca\EtudiantBundle\Entity\MatiereParUeFiliere;
use Masca\EtudiantBundle\Entity\NoteUniv;
use Masca\EtudiantBundle\Entity\UeParFiliere;
use Masca\EtudiantBundle\Entity\Universitaire;
use Masca\EtudiantBundle\Entity\UniversitaireRepository;
use Masca\EtudiantBundle\Entity\UniversitaireSonFiliere;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UniversiteImpressionController extends Controller
{
    public function ListUniversitairePrintAction(Request $request, $page) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_USER_U')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $nbParPage = $this->getParameter('nbparpage');
        /**
         * @var $repository UniversitaireRepository
         */
        $repository = $this->getDoctrine()->getManager()
            ->getRepository('MascaEtudiantBundle:Universitaire');
        /**
         * @var $universitaires Universitaire[]
         */
        $universitaires = $repository->getUniversitiares($nbParPage,$page);

        if($request->getMethod() == 'POST') {
            $universitaires = $repository->findUniversitaires($nbParPage,$page,$request->get('key_word'));
        }

        return $this->render('MascaEtudiantBundle:Impression/Universite:lsit-universite.html.twig',array(
            'universitaires'=>$universitaires,
            'page'=> $page,
            'nbPage' => ceil(count($universitaires)/$nbParPage)
        ));
    }

    public function NotePrintAction(Request $request, UniversitaireSonFiliere $universitaireSonFiliere) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_USER_U')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        /**
         * @var $listUe UeParFiliere[]
         */
        $listUe = $this->getDoctrine()->getManager()->getRepository('MascaEtudiantBundle:UeParFiliere')->findBy([
            'filiere'=>$universitaireSonFiliere->getSonFiliere(),
            'niveau'=>$universitaireSonFiliere->getSonNiveauEtude()
        ]);
        /**
         * @var $matieres MatiereParUeFiliere[][]
         */
        $matieres = [];

        foreach ($listUe as $ue) {
            $matieres[$ue->getId()] = $this->getDoctrine()->getManager()->getRepository('MascaEtudiantBundle:MatiereParUeFiliere')->findBy([
                'ueParFiliere'=>$ue
            ]);
        }

        /**
         * @var $notes NoteUniv[]
         */
        $notes = [];

        /**
         * @var float
         *
         */
        $notesUe = [];

        foreach ($matieres as $key => $matiereTab) {
            $notesUe[$key] = 0;
            $countCoef = 0;
            foreach ($matiereTab as $matiere){
                $noteTemp = $this->getDoctrine()->getRepository('MascaEtudiantBundle:NoteUniv')->findOneBy([
                    'matiere'=>$matiere,
                    'sonFiliere'=>$universitaireSonFiliere
                ]);
                $notes[$matiere->getId()] = $noteTemp;
                if($noteTemp) {
                    $notesUe[$key] += $noteTemp->getCoefficient() * $noteTemp->getMoyenne();
                    $countCoef += $noteTemp->getCoefficient();
                }
            }
            if($countCoef != 0)
                $notesUe[$key] = number_format($notesUe[$key] / $countCoef, 2, '.', '');
        }

        return $this->render('MascaEtudiantBundle:Impression/Universite:note.html.twig', [
            'sonFiliere'=>$universitaireSonFiliere,
            'listUe'=>$listUe,
            'listMatieres'=>$matieres,
            'notes'=>$notes,
            'notesUe'=>$notesUe,
            'date'=>new \DateTime()
        ]);
    }

    public function ecolagePrintAction(Request $request, UniversitaireSonFiliere $universitaireSonFiliere) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_USER_U')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }

        /**
         * @var $motantEcolage GrilleFraisScolariteUniversite
         */
        $motantEcolage = $this->getDoctrine()->getManager()
            ->getRepository('MascaEtudiantBundle:GrilleFraisScolariteUniversite')->findOneBy([
                'filiere'=>$universitaireSonFiliere->getSonFiliere(),
                'niveauEtude'=>$universitaireSonFiliere->getSonNiveauEtude()
            ]);

        if(empty($motantEcolage)) {
            return $this->render("::message-layout.html.twig",[
                'message'=>'Veuillez contacter le responsabe car il n\'y a pas un montant d\'écolage attribué au filière: '.
                    $universitaireSonFiliere->getSonFiliere()->getIntitule().' niveau '.$universitaireSonFiliere->getSonNiveauEtude()->getIntitule(),
                'previousLink'=>$request->headers->get('referer')
            ]);
        }

        /**
         * @var $statusEcolages FraisScolariteUniv[]
         */
        $statusEcolages = $this->getDoctrine()->getManager()->getRepository('MascaEtudiantBundle:FraisScolariteUniv')->findByUnivSonFiliere($universitaireSonFiliere);

        /**
         * @var $datePayements DatePayementEcolageUniv[]
         */
        $datePayements = [];

        foreach ($statusEcolages as $ecolage) {
            $datePayements[$ecolage->getId()] = $this->getDoctrine()->getManager()
                ->getRepository('MascaEtudiantBundle:DatePayementEcolageUniv')->findByFraisScolariteUniv($ecolage);
        }

        return $this->render('MascaEtudiantBundle:Impression/Universite:ecolage.html.twig', array(
            'sonFiliere'=>$universitaireSonFiliere,
            'statusEcolages'=> $statusEcolages,
            'montant'=>$motantEcolage->getMontant(),
            'datesPayement'=>$datePayements
        ));
    }
    
    public function emploiDuTempsPrintAction(Request $request, FiliereParNiveau $filiereParNiveau) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_ECO_U')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
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
                    ->getRepository('MascaEtudiantBundle:EmploiDuTempsUniv')->getMatiereBy($filiereParNiveau,$j,$h);
                if($matiereTemp != null) {
                    array_push($matieresJournalier,$matiereTemp);
                }
                else {
                    array_push($matieresJournalier,'');
                }
            }
            array_push($matieres,$matieresJournalier);
        }
        return $this->render('MascaEtudiantBundle:Impression/Universite:emploidutemps.html.twig', array(
            'jours'=>$jours,
            'heures'=>$heures,
            'nbJours'=> sizeof($jours),
            'nbHeures'=> sizeof($heures),
            'matieres'=> $matieres,
            'filiere'=>$filiereParNiveau
        ));
    }
}