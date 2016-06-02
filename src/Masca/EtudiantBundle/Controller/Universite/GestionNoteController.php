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
use Masca\EtudiantBundle\Type\NoteUnivType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

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
         * @var $notes NoteUniv[]
         */
        $notes = [];

        /**
         * @var float[]
         */
        $pourcentageEf = [];

        /**
         * @var float[]
         */
        $pourcentageFc = [];

        /**
         * @var float[]
         */
        $pourcentageNj = [];

        foreach ($matieres as $matiereTab) {
            foreach ($matiereTab as $matiere){
                $notes[$matiere->getId()] = $this->getDoctrine()->getRepository('MascaEtudiantBundle:NoteUniv')->findOneBy([
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

    /**
     * @param Request $request
     * @param MatiereParUeFiliere $matiereParUeFiliere
     * @param UniversitaireSonFiliere $universitaireSonFiliere
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/universite/formulaire-note/{matiere_id}/{son_filiere_id}", name="formulaire_note_univeritaire")
     * @ParamConverter("matiereParUeFiliere", options={"mapping": {"matiere_id":"id"}})
     * @ParamConverter("universitaireSonFiliere", options={"mapping": {"son_filiere_id":"id"}})
     */
    public function modifierNoteAction(Request $request, MatiereParUeFiliere $matiereParUeFiliere, UniversitaireSonFiliere $universitaireSonFiliere) {
        /**
         * @var $note NoteUniv
         */
        $note = $this->getDoctrine()->getManager()->getRepository('MascaEtudiantBundle:NoteUniv')->findOneBy([
            'sonFiliere'=>$universitaireSonFiliere,
            'matiere'=>$matiereParUeFiliere
        ]);
        
        if($note == null) {
            $note = new NoteUniv();
            $note->setSonFiliere($universitaireSonFiliere);
            $note->setMatiere($matiereParUeFiliere);
        }
        
        $form = $this->createForm(NoteUnivType::class,$note);
        
        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $em = $this->getDoctrine()->getManager();
            $em->persist($note);
            $em->flush();
            return $this->redirect($this->generateUrl('note_univeritaire',['id'=>$universitaireSonFiliere->getId()]));
        }
        return $this->render('MascaEtudiantBundle:Universite:formulaire-note-univ.html.twig',[
            'sonFiliere'=>$universitaireSonFiliere,
            'matiere'=>$matiereParUeFiliere,
            'form'=>$form->createView()
        ]);
    }
}