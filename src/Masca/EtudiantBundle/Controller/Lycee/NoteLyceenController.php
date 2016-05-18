<?php
/**
 * Created by PhpStorm.
 * User: patmi
 * Date: 16/05/2016
 * Time: 20:43
 */

namespace Masca\EtudiantBundle\Controller\Lycee;


use Masca\EtudiantBundle\Entity\Lyceen;
use Masca\EtudiantBundle\Entity\LyceenNote;
use Masca\EtudiantBundle\Type\LyceenNoteType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class NoteLyceenController extends Controller
{
    /**
     * @param Lyceen $lyceen
     * @return \Symfony\Component\HttpFoundation\Response
     * @ParamConverter("lyceen", options={"mapping": {"lyceen_id":"id"}})
     * @Route("/lycee/note/liste/{lyceen_id}", name="liste_notes_lyceen")
     */
    public function noteAction(Lyceen $lyceen) {
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
     * @Route("/lycee/note/ajouter/{lyceen_id}", name="ajouter_note_lyceen")
     */
    public function ajouterNoteAction(Request $request, Lyceen $lyceen) {

        $note = new LyceenNote();
        $form = $this->createForm(LyceenNoteType::class,$note);

        if($request->getMethod() ==  'POST') {
            $form->handleRequest($request);
            $note->setLyceen($lyceen);
            if(!$this->getDoctrine()->getManager()
                ->getRepository('MascaEtudiantBundle:LyceenNote')->isValid($lyceen, $note->getMatiere())) {
                $js = '<script  type="text/javascript">'.
                    'document.getElementById("DivInfo").style.display = "block";'.
                    '</script>';
                return $this->render('MascaEtudiantBundle:Lycee:ajoute-note.html.twig', [
                    'form'=>$form->createView(),
                    'lyceen'=>$lyceen,
                    'js'=>$js
                ]);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($note);
            $em->flush();
            return $this->redirect($this->generateUrl('liste_notes_lyceen',['lyceen_id'=>$lyceen->getId()]));
        }
        return $this->render('MascaEtudiantBundle:Lycee:ajoute-note.html.twig', [
            'form'=>$form->createView(),
            'lyceen'=>$lyceen
        ]);
    }

}