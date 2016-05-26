<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/26/16
 * Time: 1:23 PM
 */

namespace Masca\EtudiantBundle\Controller\Universite;


use Doctrine\DBAL\Exception\ConstraintViolationException;
use Masca\EtudiantBundle\Entity\AbsenceUniv;
use Masca\EtudiantBundle\Entity\Universitaire;
use Masca\EtudiantBundle\Type\AbsenceUnivType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class AbsenceUnivController extends Controller
{

    /**
     * @param Universitaire $universitaire
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/universite/absence/{id}", name="absence_universitaire")
     */
    public function indexAction(Universitaire $universitaire) {

        /**
         * @var $listAbs AbsenceUniv[]
         */
        $listAbs = $this->getDoctrine()->getManager()->getRepository('MascaEtudiantBundle:AbsenceUniv')->findByUniversitaire($universitaire);
        return $this->render('MascaEtudiantBundle:Universite:absence.html.twig', [
            'listAbs'=> $listAbs,
            'universitaire'=>$universitaire
        ]);
    }

    /**
     * @param Request $request
     * @param Universitaire $universitaire
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/universite/absence/creer/{id}", name="enregistrement_absence_universitaire")
     */
    public function enregistrementAbsenceAction(Request $request, Universitaire $universitaire) {
        $absence = new AbsenceUniv();
        $form = $this->createForm(AbsenceUnivType::class, $absence);

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $absence->setUniversitaire($universitaire);
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($absence);
                $em->flush();
            } catch (ConstraintViolationException $e) {
                return $this->render('MascaEtudiantBundle:Universite:formulaire-absence.html.twig',[
                    'form'=>$form->createView(),
                    'universitaire'=>$universitaire,
                    'error_message'=>'L\'abcense avec ces information pour '.$universitaire->getPerson()->getPrenom().' '.$universitaire->getPerson()->getNom().' est déjà enregistré'
                ]);
            }
            return $this->redirect($this->generateUrl('absence_universitaire',['id'=>$universitaire->getId()]));
        }

        return $this->render('MascaEtudiantBundle:Universite:formulaire-absence.html.twig',[
            'form'=>$form->createView(),
            'universitaire'=>$universitaire
        ]);
    }


    /**
     * @param Request $request
     * @param AbsenceUniv $absenceUniv
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/universite/absence/modifier/{id}", name="modifier_absence_universitaire")
     */
    public function modificationAbsenceAction(Request $request, AbsenceUniv $absenceUniv) {
        $form = $this->createForm(AbsenceUnivType::class, $absenceUniv);
        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            try {
                $this->getDoctrine()->getManager()->flush();
            }  catch (ConstraintViolationException $e) {
                return $this->render('MascaEtudiantBundle:Universite:formulaire-absence.html.twig',[
                    'form'=>$form->createView(),
                    'universitaire'=>$absenceUniv->getUniversitaire(),
                    'error_message'=>'L\'abcense avec ces information pour '.$absenceUniv->getUniversitaire()->getPerson()->getPrenom().' '.$absenceUniv->getUniversitaire()->getPerson()->getNom().' est déjà enregistré'
                ]);
            }
            return $this->redirect($this->generateUrl('absence_universitaire',['id'=>$absenceUniv->getUniversitaire()->getId()]));
        }
        return $this->render('MascaEtudiantBundle:Universite:formulaire-absence.html.twig',[
            'form'=>$form->createView(),
            'universitaire'=>$absenceUniv->getUniversitaire()
        ]);
    }

    /**
     * @param AbsenceUniv $absenceUniv
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/universite/absence/supprimer/{id}", name="supprimer_absence_universitaire")
     */
    public function supprimerAbsenceAction(AbsenceUniv $absenceUniv) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($absenceUniv);
        $em->flush();
        return $this->redirect($this->generateUrl('absence_universitaire',['id'=>$absenceUniv->getUniversitaire()->getId()]));
    }
}