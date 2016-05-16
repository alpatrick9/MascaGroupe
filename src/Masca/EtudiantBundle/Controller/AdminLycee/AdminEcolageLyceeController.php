<?php
/**
 * Created by PhpStorm.
 * User: patmi
 * Date: 16/05/2016
 * Time: 20:22
 */

namespace Masca\EtudiantBundle\Controller\AdminLycee;


use Masca\EtudiantBundle\Entity\GrilleFraisScolariteLycee;
use Masca\EtudiantBundle\Entity\GrilleFraisScolariteLyceeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\Request;

class AdminEcolageLyceeController extends Controller
{
    /**
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/lycee/admin/grille/ecolage/{page}", name="grille_ecolage_lycee", defaults={"page" = 1})
     */
    public function grilleEcolageAction($page) {
        $nbParPage = 30;
        /**
         * @var $repository GrilleFraisScolariteLyceeRepository
         */
        $repository = $this->getDoctrine()->getManager()
            ->getRepository('MascaEtudiantBundle:GrilleFraisScolariteLycee');
        /**
         * @var $grilles GrilleFraisScolariteLycee[]
         */
        $grilles = $repository->getGrilles($nbParPage,$page);

        return $this->render('MascaEtudiantBundle:Admin_lycee:grilles-frais-scolarite.html.twig',array(
            'grilles'=>$grilles,
            'page'=> $page,
            'nbPage' => ceil(count($grilles)/$nbParPage)));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/lycee/admin/ajouter-grille-ecolage/", name="ajouter_grille_ecolage_lycee")
     */
    public function creerGrilleEcolageAction(Request $request) {
        $grille = new GrilleFraisScolariteLycee();
        $grilleFormBuilder = $this->createFormBuilder($grille);
        $grilleFormBuilder
            ->add('classe',EntityType::class,array(
                'label'=>'Classe correspondant',
                'class'=>'Masca\EtudiantBundle\Entity\Classe',
                'choice_label'=>'intitule',
                'placeholder'=>'Choisissez..'
            ))
            ->add('montant',NumberType::class,array(
                'label'=>'Montant en (Ar)'
            ));
        $grilleForm = $grilleFormBuilder->getForm();

        if($request->getMethod() == 'POST') {
            $grilleForm->handleRequest($request);
            if($this->getDoctrine()->getManager()->getRepository('MascaEtudiantBundle:GrilleFraisScolariteLycee')
                    ->findOneBy(['classe'=>$grille->getClasse()]) != null) {
                return $this->render('MascaEtudiantBundle:Admin_lycee:creer-grille-ecolage.html.twig', array(
                    'grilleForm'=>$grilleForm->createView(),
                    'error_message'=>'La grille de frais de scolarite de la classe '.$grille->getClasse()->getIntitule().' existe déjà, choisissez une autre classe'
                ));
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($grille);
            $em->flush();
            return $this->redirect($this->generateUrl('grille_ecolage_lycee'));
        }
        return $this->render('MascaEtudiantBundle:Admin_lycee:creer-grille-ecolage.html.twig', array(
            'grilleForm'=>$grilleForm->createView()
        ));
    }

    /**
     * @param Request $request
     * @param GrilleFraisScolariteLycee $grille
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @ParamConverter("grille", options={"mapping": {"grille_id": "id"}})
     * @Route("/lycce/admin/modifier-grille-ecolage/{grille_id}", name="modifier_grille_ecolage_lycee")
     */
    public function modifierGrilleEcolageAction(Request $request, GrilleFraisScolariteLycee $grille) {
        $grilleFormBuilder = $this->createFormBuilder($grille);
        $grilleFormBuilder
            ->add('classe',EntityType::class,array(
                'label'=>'Classe correspondant',
                'class'=>'Masca\EtudiantBundle\Entity\Classe',
                'choice_label'=>'intitule',
                'placeholder'=>'Choisissez..',
                'data'=>$grille->getClasse(),
                'attr'=>['readonly'=>true]
            ))
            ->add('montant',NumberType::class,array(
                'label'=>'Montant en (Ar)',
                'data'=>$grille->getMontant()
            ));
        $grilleForm = $grilleFormBuilder->getForm();

        if($request->getMethod() == 'POST') {
            $grilleForm->handleRequest($request);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirect($this->generateUrl('grille_ecolage_lycee'));
        }
        return $this->render('MascaEtudiantBundle:Admin_lycee:creer-grille-ecolage.html.twig', array(
            'grilleForm'=>$grilleForm->createView()
        ));
    }
}