<?php
/**
 * Created by PhpStorm.
 * User: patmi
 * Date: 16/05/2016
 * Time: 20:22
 */

namespace Masca\EtudiantBundle\Controller\AdminLycee;


use Doctrine\DBAL\Exception\ConstraintViolationException;
use Masca\EtudiantBundle\Entity\GrilleFraisScolariteLycee;
use Masca\EtudiantBundle\Entity\GrilleFraisScolariteLyceeRepository;
use Masca\EtudiantBundle\Type\GrilleEcolageLyceeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AdminEcolageLyceeController
 * @package Masca\EtudiantBundle\Controller\AdminLycee
 * @Route("/lycee/admin/ecolage")
 */
class AdminEcolageLyceeController extends Controller
{
    /**
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/grille/{page}", name="grille_ecolage_lycee", defaults={"page" = 1})
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
     * @Route("/ajouter-grille/", name="ajouter_grille_ecolage_lycee")
     */
    public function creerGrilleEcolageAction(Request $request) {
        $grille = new GrilleFraisScolariteLycee();

        $grilleForm = $this->createForm(GrilleEcolageLyceeType::class,$grille);

        if($request->getMethod() == 'POST') {
            $grilleForm->handleRequest($request);
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($grille);
                $em->flush();
            } catch (ConstraintViolationException $e) {
                return $this->render('MascaEtudiantBundle:Admin_lycee:formulaire-grille-ecolage.html.twig', array(
                    'form'=>$grilleForm->createView(),
                    'error_message'=>'La grille de frais de scolarite de la classe '.$grille->getClasse()->getIntitule().' existe déjà, choisissez une autre classe'
                ));
            }
            return $this->redirect($this->generateUrl('grille_ecolage_lycee'));
        }
        return $this->render('MascaEtudiantBundle:Admin_lycee:formulaire-grille-ecolage.html.twig', array(
            'form'=>$grilleForm->createView()
        ));
    }

    /**
     * @param Request $request
     * @param GrilleFraisScolariteLycee $grille
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @ParamConverter("grille", options={"mapping": {"grille_id": "id"}})
     * @Route("/modifier-grille/{grille_id}", name="modifier_grille_ecolage_lycee")
     */
    public function modifierGrilleEcolageAction(Request $request, GrilleFraisScolariteLycee $grille) {
        $grilleForm = $this->createForm(GrilleEcolageLyceeType::class, $grille);
        $classeField = $grilleForm->get('classe');
        $options = $classeField->getConfig()->getOptions();
        $options['disabled'] = true;
        $grilleForm->add('classe',EntityType::class,$options);

        if($request->getMethod() == 'POST') {
            $grilleForm->handleRequest($request);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirect($this->generateUrl('grille_ecolage_lycee'));
        }
        return $this->render('MascaEtudiantBundle:Admin_lycee:formulaire-grille-ecolage.html.twig', array(
            'form'=>$grilleForm->createView()
        ));
    }
}