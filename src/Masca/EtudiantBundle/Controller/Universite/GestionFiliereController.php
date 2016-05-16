<?php
/**
 * Created by PhpStorm.
 * User: patmi
 * Date: 16/05/2016
 * Time: 20:48
 */

namespace Masca\EtudiantBundle\Controller\Universite;


use Masca\EtudiantBundle\Entity\UniversitaireSonFiliere;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class GestionFiliereController extends Controller
{
    /**
     * @param Request $request
     * @param UniversitaireSonFiliere $sonFiliere
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/universite/reinscription/{id}", name="reinscription_universite")
     */
    public function reinscriptionAction(Request $request, UniversitaireSonFiliere $sonFiliere) {
        $sonFiliereFormBuilder = $this->createFormBuilder($sonFiliere);
        $sonFiliereFormBuilder
            ->add('semestre',EntityType::class,array(
                'label'=>'Semestre',
                'class'=>'Masca\EtudiantBundle\Entity\Semestre',
                'choice_label'=>'intitule',
                'placeholder'=>'choisissez...',
                'data'=>$sonFiliere->getSemestre()
            ))
            ->add('sonFiliere',EntityType::class,array(
                'label'=>'Filière',
                'class'=>'Masca\EtudiantBundle\Entity\Filiere',
                'choice_label'=>'intitule',
                'placeholder'=>'Choisissez...',
                'data'=>$sonFiliere->getSonFiliere(),
                'attr'=>['readonly'=>true]
            ))
            ->add('sonNiveauEtude',EntityType::class,array(
                'label'=>'Niveau d\'etude',
                'class'=>'Masca\EtudiantBundle\Entity\NiveauEtude',
                'choice_label'=>'intitule',
                'placeholder'=>'Choisissez...',
                'data'=>$sonFiliere->getSonNiveauEtude()
            ))
            ->add('anneeEtude',IntegerType::class,array(
                'label'=>'Année d\'etude',
                'attr'=>array('min'=>1900),
                'data'=>$sonFiliere->getAnneeEtude()
            ))
            ->add('dateReinscription',DateType::class,array(
                'label'=>'Date de réinscription',
                'format'=>'dd MMMM yyyy',
                'years'=>range(date('Y')-1,date('Y')+5),
                'placeholder'=>array('year'=>'Année','day'=>'Jour','month'=>'Mois'),
                'data'=>$sonFiliere->getDateReinscription()
            ));
        $sonFiliereForm = $sonFiliereFormBuilder->getForm();

        if($request->getMethod() == 'POST') {
            $sonFiliereForm->handleRequest($request);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirect($this->generateUrl('details_etude_universitaire', array('sonFiliere_id'=>$sonFiliere->getId())));
        }

        return $this->render('MascaEtudiantBundle:Universite:reinscription.html.twig',array(
            'sonFiliereForm'=>$sonFiliereForm->createView(),
            'fullEtudantName'=> $sonFiliere->getUniversitaire()->getPerson()->getPrenom() ." ".$sonFiliere->getUniversitaire()->getPerson()->getNom(),
            'universitaireId'=>$sonFiliere->getUniversitaire()->getId(),
            'sonFiliereId'=> $sonFiliere->getId()
        ));
    }

    /**
     * @param UniversitaireSonFiliere $sonFiliere
     * @return \Symfony\Component\HttpFoundation\Response
     * @ParamConverter("sonFiliere", options={"mapping": {"sonFiliere_id":"id"}})
     * @Route("/universite/details-etude/{sonFiliere_id}", name="details_etude_universitaire")
     */
    public function detailsEtudeAction(UniversitaireSonFiliere $sonFiliere) {
        return $this->render('MascaEtudiantBundle:Universite:details-etude.html.twig',[
            'sonFiliere'=>$sonFiliere
        ]);
    }
}