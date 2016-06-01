<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 6/1/16
 * Time: 2:28 PM
 */

namespace Masca\EtudiantBundle\Controller\Universite;


use Masca\EtudiantBundle\Entity\MatiereParUeFiliere;
use Masca\EtudiantBundle\Entity\NoteUniv;
use Masca\EtudiantBundle\Entity\UeParFiliere;
use Masca\EtudiantBundle\Entity\UniversitaireSonFiliere;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class GestionNoteController extends Controller
{
    /**
     * @param UniversitaireSonFiliere $universitaireSonFiliere
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/universite/note/{id}", name="note_univeritaire")
     */
    public function indexAction(UniversitaireSonFiliere $universitaireSonFiliere) {
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
         * @var NoteUniv[]
         */
        $notes = [];

        foreach ($matieres as $matiereTab) {
            foreach ($matiereTab as $matiere){
                $notes[$matiere->getId()] = $this->getDoctrine()->getRepository('MascaEtudiantBundle:NoteUniv')->findBy([
                    'matiere'=>$matiere,
                    'sonFiliere'=>$universitaireSonFiliere
                ]);
            }
        }
        
        return $this->render('MascaEtudiantBundle:Universite:note-univ.html.twig', [
            'sonFiliere'=>$universitaireSonFiliere,
            'listUe'=>$listUe,
            'listMatieres'=>$matieres,
            'notes'=>$notes
        ]);
    }
}