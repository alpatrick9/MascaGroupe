<?php
/**
 * Created by PhpStorm.
 * User: patmi
 * Date: 16/05/2016
 * Time: 20:43
 */

namespace Masca\EtudiantBundle\Controller\Lycee;


use Doctrine\DBAL\Exception\ConstraintViolationException;
use Masca\EtudiantBundle\Entity\Lyceen;
use Masca\EtudiantBundle\Entity\LyceenNote;
use Masca\EtudiantBundle\Type\LyceenNoteType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class NoteLyceenController
 * @package Masca\EtudiantBundle\Controller\Lycee
 * @Route("/lycee/note")
 */
class NoteLyceenController extends Controller
{
    /**
     * @param Request $request
     * @param Lyceen $lyceen
     * @return \Symfony\Component\HttpFoundation\Response
     * @ParamConverter("lyceen", options={"mapping": {"lyceen_id":"id"}})
     * @Route("/liste/{lyceen_id}", name="liste_notes_lyceen")
     */
    public function noteAction(Request $request,Lyceen $lyceen) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_SG')){
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

        $moyenGeneral = ($moyenneTrimestre1 + $moyenneTrimestre2 + $moyenneTrimestre3)/3;
        return $this->render('MascaEtudiantBundle:Lycee:notes.html.twig',[
            'notes'=>$notes,
            'lyceen'=>$lyceen,
            'totalCoef'=>$totalCoef,
            'totalTrimestre1'=>$totalTrimestre1,
            'totalTrimestre2'=>$totalTrimestre2,
            'totalTrimestre3'=>$totalTrimestre3,
            'moyenne1'=>$moyenneTrimestre1,
            'moyenne2'=>$moyenneTrimestre2,
            'moyenne3'=>$moyenneTrimestre3,
            'moyenneGeneral'=>$moyenGeneral
        ]);
    }

    /**
     * @param Request $request
     * @param Lyceen $lyceen
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @ParamConverter("lyceen", options={"mapping":{"lyceen_id":"id"}})
     * @Route("/ajouter/{lyceen_id}", name="ajouter_note_lyceen")
     */
    public function ajouterNoteAction(Request $request, Lyceen $lyceen) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_SG')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }

        $note = new LyceenNote();
        $form = $this->createForm(LyceenNoteType::class,$note);

        if($request->getMethod() ==  'POST') {
            $form->handleRequest($request);
            $note->setLyceen($lyceen);
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($note);
                $em->flush();
            } catch (ConstraintViolationException $e) {
                return $this->render('MascaEtudiantBundle:Lycee:formulaire-note.html.twig', [
                    'form'=>$form->createView(),
                    'lyceen'=>$lyceen,
                    'error_message'=>'Matiere '.$note->getMatiere()->getIntitule().' existe déjà'
                ]);
            }

            return $this->redirect($this->generateUrl('liste_notes_lyceen',['lyceen_id'=>$lyceen->getId()]));
        }
        return $this->render('MascaEtudiantBundle:Lycee:formulaire-note.html.twig', [
            'form'=>$form->createView(),
            'lyceen'=>$lyceen
        ]);
    }

    /**
     * @param Request $request
     * @param LyceenNote $lyceenNote
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @ParamConverter("lyceenNote", options={"mapping":{"lyceenNote_id":"id"}})
     * @Route("/modifier/{lyceenNote_id}", name="modifier_note_lyceen")
     */
    public function modifierNoteAction(Request $request, LyceenNote $lyceenNote) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_SG')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $form = $this->createForm(LyceenNoteType::class, $lyceenNote);
        $matiereField = $form->get('matiere');
        $options = $matiereField->getConfig()->getOptions();
        $options['disabled'] = true;
        $form->add('matiere',EntityType::class,$options);

        if($request->getMethod() ==  'POST') {
            $form->handleRequest($request);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirect($this->generateUrl('liste_notes_lyceen',['lyceen_id'=>$lyceenNote->getLyceen()->getId()]));
        }
        return $this->render('MascaEtudiantBundle:Lycee:formulaire-note.html.twig', [
            'form'=>$form->createView(),
            'lyceen'=>$lyceenNote->getLyceen()
        ]);
    }

    /**
     * @param Request $request
     * @param LyceenNote $lyceenNote
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/supprimer/{id}", name="supprimer_note_lyceen")
     */
    public function deleteNoteLyceenAction(Request $request, LyceenNote $lyceenNote) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_SG')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($lyceenNote);
        $em->flush();
        return $this->redirect($this->generateUrl('liste_notes_lyceen',['lyceen_id'=>$lyceenNote->getLyceen()->getId()]));
    }

    /**
     * @param Request $request
     * @param Lyceen $lyceen
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/print/{id}", name="print_notes_lyceen")
     */
    public function printNoteAction(Request $request, Lyceen $lyceen) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_SG')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }

        $html = $this->forward('MascaEtudiantBundle:Lycee/Impression/LyceeImpression:noteLyceePrint',[
            'lyceen'=>$lyceen
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