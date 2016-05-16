<?php
/**
 * Created by PhpStorm.
 * User: patmi
 * Date: 16/05/2016
 * Time: 20:40
 */

namespace Masca\EtudiantBundle\Controller\Lycee;


use Masca\EtudiantBundle\Entity\FraisScolariteLyceen;
use Masca\EtudiantBundle\Entity\GrilleFraisScolariteLycee;
use Masca\EtudiantBundle\Entity\Lyceen;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Request;

class EcolageLyceenController extends Controller
{
    /**
     * @param Lyceen $lyceen
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/lycee/ecolage/accueil/{id}", name="ecolage_lyceen")
     */
    public function ecolageAction(Lyceen $lyceen) {
        /**
         * @var $motantEcolage GrilleFraisScolariteLycee
         */
        $motantEcolage = $this->getDoctrine()->getManager()
            ->getRepository('MascaEtudiantBundle:GrilleFraisScolariteLycee')->findOneByClasse($lyceen->getSonClasse());

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

        return $this->render('MascaEtudiantBundle:Lycee:ecolage.html.twig', array(
            'lyceen'=>$lyceen,
            'statusEcolages'=> $statusEcolages,
            'montant'=>$motantEcolage->getMontant()
        ));
    }

    /**
     * @param Request $request
     * @param Lyceen $lyceen
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/lycee/ecolage/payer/{id}", name="payer_ecolage_lyceen")
     */
    public function payerEcolageAction(Request $request, Lyceen $lyceen) {
        $anneeData = explode('-',$lyceen->getAnneeScolaire());
        $choicesAnnee = array($anneeData[0]=>$anneeData[0],$anneeData[1]=>$anneeData[1]);

        /**
         * @var $motantEcolage GrilleFraisScolariteLycee
         */
        $motantEcolage = $this->getDoctrine()->getManager()
            ->getRepository('MascaEtudiantBundle:GrilleFraisScolariteLycee')->findOneByClasse($lyceen->getSonClasse());

        $fraisScolariteLyceen = new FraisScolariteLyceen();
        $fraisScolariteLyceen->setAnneeScolaire($lyceen->getAnneeScolaire());
        $fraisScolariteLyceen->setLyceen($lyceen);

        $ecolageFromBuilder = $this->createFormBuilder($fraisScolariteLyceen);
        $ecolageFromBuilder
            ->add('mois',ChoiceType::class,array(
                'label'=>'Mois',
                'choices_as_values'=>true,
                'choices'=>array('Janvier'=>'Janvier','Fevrier'=>'Février','Mars'=>'Mars','Mai'=>'Mai','Juin'=>'Juin','Juillet'=>'Juillet','Aout'=>'Août','Septembre'=>'Septembre','Novermbre'=>'Novembre','Decembre'=>'Decembre'),
                'placeholder'=>'Choisissez...',
                'data'=>$fraisScolariteLyceen->getMois()
            ))
            ->add('annee',ChoiceType::class,array(
                'label'=>'Annee',
                'choices_as_values'=>true,
                'choices'=> $choicesAnnee,
                'placeholder'=>'Choisissez...',
                'data'=>$fraisScolariteLyceen->getAnnee()
            ))
            ->add('montant',IntegerType::class,array(
                'label'=>'Montant',
                'attr'=>array(
                    'placeholder'=>'la somme',
                    'min'=>0,
                    'max'=>$motantEcolage->getMontant()
                )
            ));
        $ecolageFrom = $ecolageFromBuilder->getForm();

        if($request->getMethod() == 'POST') {
            $ecolageFrom->handleRequest($request);
            if($fraisScolariteLyceen->getMontant() == $motantEcolage->getMontant()) {
                $fraisScolariteLyceen->setStatus(true);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($fraisScolariteLyceen);
            $em->flush();
            return $this->redirect($this->generateUrl('ecolage_lyceen', array('id'=>$lyceen->getId())));
        }
        return $this->render('MascaEtudiantBundle:Lycee:payementecolage.html.twig', array(
            'ecolageForm'=>$ecolageFrom->createView(),
            'lyceen'=>$lyceen,
            'montant'=>$motantEcolage->getMontant()
        ));
    }

    /**
     * @param Request $request
     * @param FraisScolariteLyceen $fraisScolariteLyceen
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @ParamConverter("fraisScolariteLyceen", options={"mapping": {"frais_scolarite_id":"id"}})
     * @Route("/lycee/ecolage/regularisation-reste/{frais_scolarite_id}", name="regularisation_reste_ecolage_lyceen")
     */
    public function regularisationResteEcolageAction(Request $request, FraisScolariteLyceen $fraisScolariteLyceen) {
        $anneeData = explode('-',$fraisScolariteLyceen->getLyceen()->getAnneeScolaire());
        $choicesAnnee = array($anneeData[0]=>$anneeData[0],$anneeData[1]=>$anneeData[1]);

        /**
         * @var $motantEcolage GrilleFraisScolariteLycee
         */
        $motantEcolage = $this->getDoctrine()->getManager()
            ->getRepository('MascaEtudiantBundle:GrilleFraisScolariteLycee')->findOneByClasse($fraisScolariteLyceen->getLyceen()->getSonClasse());

        $ecolageFromBuilder = $this->createFormBuilder($fraisScolariteLyceen);
        $ecolageFromBuilder
            ->add('mois',ChoiceType::class,array(
                'label'=>'Mois',
                'choices_as_values'=>true,
                'choices'=>array('Janvier'=>'Janvier','Fevrier'=>'Février','Mars'=>'Mars','Mai'=>'Mai','Juin'=>'Juin','Juillet'=>'Juillet','Aout'=>'Août','Septembre'=>'Septembre','Novermbre'=>'Novembre','Decembre'=>'Decembre'),
                'placeholder'=>'Choisissez...',
                'data'=>$fraisScolariteLyceen->getMois(),
                'attr'=>['readonly'=>true]
            ))
            ->add('annee',ChoiceType::class,array(
                'label'=>'Annee',
                'choices_as_values'=>true,
                'choices'=> $choicesAnnee,
                'placeholder'=>'Choisissez...',
                'data'=>$fraisScolariteLyceen->getAnnee(),
                'attr'=>['readonly'=>true]
            ))
            ->add('montant',IntegerType::class,array(
                'label'=>'Complement de l\'ecolage',
                'attr'=>array(
                    'placeholder'=>'la somme',
                    'min'=>0,
                    'max'=>$motantEcolage->getMontant()-$fraisScolariteLyceen->getMontant()
                ),
                'data'=>null
            ));
        $ecolageFrom = $ecolageFromBuilder->getForm();

        if($request->getMethod() == 'POST') {
            $oldMontant = $fraisScolariteLyceen->getMontant();
            $ecolageFrom->handleRequest($request);
            $fraisScolariteLyceen->setMontant($oldMontant + $fraisScolariteLyceen->getMontant());
            if($fraisScolariteLyceen->getMontant() == $motantEcolage->getMontant())
                $fraisScolariteLyceen->setStatus(true);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirect($this->generateUrl('ecolage_lyceen',array('id'=>$fraisScolariteLyceen->getLyceen()->getId())));
        }
        return $this->render('MascaEtudiantBundle:Lycee:regularisation-reste-ecolage.html.twig', array(
            'form'=>$ecolageFrom->createView(),
            'renseignement'=>$fraisScolariteLyceen,
            'reste'=>$motantEcolage->getMontant()-$fraisScolariteLyceen->getMontant()
        ));
    }

}