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
use Symfony\Component\HttpFoundation\Response;

/**
 * Class GestionNoteController
 * @package Masca\EtudiantBundle\Controller\Universite
 * @Route("/universite/note")
 */
class GestionNoteController extends Controller
{
    /**
     * @param UniversitaireSonFiliere $universitaireSonFiliere
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/{id}", name="note_univeritaire")
     */
    public function indexAction(UniversitaireSonFiliere $universitaireSonFiliere, Request $request) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_SG_U')){
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
        
        return $this->render('MascaEtudiantBundle:Universite:note-univ.html.twig', [
            'sonFiliere'=>$universitaireSonFiliere,
            'listUe'=>$listUe,
            'listMatieres'=>$matieres,
            'notes'=>$notes,
            'notesUe'=>$notesUe
        ]);
    }

    /**
     * @param Request $request
     * @param MatiereParUeFiliere $matiereParUeFiliere
     * @param UniversitaireSonFiliere $universitaireSonFiliere
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/modifier/{matiere_id}/{son_filiere_id}", name="formulaire_note_univeritaire")
     * @ParamConverter("matiereParUeFiliere", options={"mapping": {"matiere_id":"id"}})
     * @ParamConverter("universitaireSonFiliere", options={"mapping": {"son_filiere_id":"id"}})
     */
    public function modifierNoteAction(Request $request, MatiereParUeFiliere $matiereParUeFiliere, UniversitaireSonFiliere $universitaireSonFiliere) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_SG_U')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
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
            $moyenne = ($note->getNoteEF() * $note->getNoteFC() * $note->getNoteNJ()) / (0.6 * $note->getNoteFC() * $note->getNoteNJ() + 0.3 * $note->getNoteEF() *$note->getNoteNJ() + 0.1 * $note->getNoteEF() * $note->getNoteFC());
            $note->setMoyenne(number_format($moyenne, 2, '.', ''));
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

    /**
     * @param Request $request
     * @param NoteUniv $noteUniv
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/supprimer/note/{id}", name="supprimer_note_univ")
     */
    public function supprimerNoteUnivAction (Request $request, NoteUniv $noteUniv) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($noteUniv);
        $em->flush();
        return $this->redirect($this->generateUrl('note_univeritaire',['id'=>$noteUniv->getSonFiliere()->getId()]));
    }

    /**
     * @param Request $request
     * @param UniversitaireSonFiliere $universitaireSonFiliere
     * @return Response
     * @Route("/print/note/{id}", name="print_note_université")
     */
    public function printNoteUnivAction(Request $request,UniversitaireSonFiliere $universitaireSonFiliere) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }

        $html = $this->forward('MascaEtudiantBundle:Universite/Impression/UniversiteImpression:NotePrint',[
            'universitaireSonFiliere'=>$universitaireSonFiliere
        ])->getContent();

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => sprintf('inline; filename="%s"', 'out.pdf'),
            ]
        );
    }
}