<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 9/16/16
 * Time: 9:43 AM
 */

namespace Masca\TresorBundle\Controller\Universite;


use Doctrine\DBAL\Exception\ConstraintViolationException;
use Masca\TresorBundle\Entity\MvmtLycee;
use Masca\TresorBundle\Entity\MvmtUniversite;
use Masca\TresorBundle\Entity\SoldeLycee;
use Masca\TresorBundle\Entity\SoldeUniversite;
use Masca\TresorBundle\Type\MvmtLyceeType;
use Masca\TresorBundle\Type\MvmtUniversiteType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CaisseUniversiteController
 * @package Masca\TresorBundle\Controller\Universite
 * @Route("/universite")
 */
class CaisseUniversiteController extends Controller
{

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="home_caisse_universite", defaults={"page" = 1})
     */
    public function indexAction(Request $request, $page) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')) {
            return $this->render("::message-layout.html.twig", [
                'message' => 'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink' => $request->headers->get('referer')
            ]);
        }
        $nbParPage = 30;
        /**
         * @var $mvmts MvmtUniversite[]
         */
        $mvmts = $this->getDoctrine()->getRepository('MascaTresorBundle:MvmtUniversite')->getMouvements($nbParPage,$page);

        /**
         * @var $solde SoldeUniversite
         */
        $solde = $this->getDoctrine()->getRepository('MascaTresorBundle:SoldeUniversite')->find(1);

        if(empty($solde)) {
            $solde = new SoldeUniversite();
            $em = $this->getDoctrine()->getManager();
            $em->persist($solde);
            $em->flush();
        }

        return $this->render('MascaTresorBundle:Universite:index.html.twig',[
            'mvmts'=>$mvmts,
            'page'=> $page,
            'nbPage' => ceil(count($mvmts)/$nbParPage),
            'solde'=>$solde
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/new", name="add_mvmt_universite")
     */
    public function addMvmtUniversiteAction(Request $request) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')) {
            return $this->render("::message-layout.html.twig", [
                'message' => 'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink' => $request->headers->get('referer')
            ]);
        }
        
        $mvmt = new MvmtUniversite();
        
        $form = $this->createForm(new MvmtUniversiteType($this->getParameter('operation')),$mvmt);

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if($mvmt->getSomme() <= 0) {
                return $this->render('MascaTresorBundle:Universite:formulaire_operation.html.twig',[
                    'form'=>$form->createView(),
                    'error_message' => 'La somme entrée "'.$mvmt->getSomme().'" n\'est pas valide! '
                ]);
            }
            $em = $this->getDoctrine()->getManager();
            try{
                /**
                 * @var $solde SoldeUniversite
                 */
                $solde = $this->getDoctrine()->getRepository('MascaTresorBundle:SoldeUniversite')->find(1);
                $solde->setDate($mvmt->getDate());

                $mvmt->setSoldePrecedent($solde->getSolde());

                switch ($mvmt->getTypeOperation()) {
                    case 'd':
                        $solde->setSolde($solde->getSolde() - $mvmt->getSomme());
                        break;
                    case 'c':
                        $solde->setSolde($solde->getSolde() + $mvmt->getSomme());
                        break;
                }
                $mvmt->setSoldeApres($solde->getSolde());
                $em->persist($mvmt);
                $em->flush();

            } catch (ConstraintViolationException $e) {
                return $this->render('MascaTresorBundle:Universite:formulaire_operation.html.twig',[
                    'form'=>$form->createView(),
                    'error_message' => 'Une erreur d\'enregistrement vient de se produire, veuillez reesseyez svp! '
                ]);
            }
            return $this->redirect($this->generateUrl('home_caisse_universite'));
        }
        return $this->render('MascaTresorBundle:Universite:formulaire_operation.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/virement", name="virement_to_lycee")
     */
    public function virementToLyceeAction(Request $request) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')) {
            return $this->render("::message-layout.html.twig", [
                'message' => 'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink' => $request->headers->get('referer')
            ]);
        }
        $mvmt = new MvmtUniversite();
        $mvmt->setTypeOperation('d');
        $mvmt->setDescription('Virement compte universitaire à compte Lycée');

        $form = $this->createForm(new MvmtUniversiteType($this->getParameter('operation')),$mvmt);

        $descriptionField = $form->get('description');
        $options = $descriptionField->getConfig()->getOptions();
        $options['disabled'] = true;
        $form->add('description', TextType::class, $options);

        $typeOperationField = $form->get('typeOperation');
        $options = $typeOperationField->getConfig()->getOptions();
        $options['disabled'] = true;
        $form->add('typeOperation', ChoiceType::class, $options);

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if($mvmt->getSomme() <= 0) {
                return $this->render('MascaTresorBundle:Universite:formulaire_operation.html.twig',[
                    'form'=>$form->createView(),
                    'error_message' => 'La somme entrée "'.$mvmt->getSomme().'" n\'est pas valide! '
                ]);
            }
            $em = $this->getDoctrine()->getManager();
            try{
                /**
                 * @var $solde SoldeUniversite
                 */
                $solde = $this->getDoctrine()->getRepository('MascaTresorBundle:SoldeUniversite')->find(1);
                $solde->setDate($mvmt->getDate());

                $mvmt->setSoldePrecedent($solde->getSolde());

                switch ($mvmt->getTypeOperation()) {
                    case 'd':
                        $solde->setSolde($solde->getSolde() - $mvmt->getSomme());
                        break;
                    case 'c':
                        $solde->setSolde($solde->getSolde() + $mvmt->getSomme());
                        break;
                }
                $mvmt->setSoldeApres($solde->getSolde());
                $em->persist($mvmt);

                $mvmtLycee = new MvmtLycee();
                $mvmtLycee->setDescription($mvmt->getDescription());
                $mvmtLycee->setTypeOperation('c');
                $mvmtLycee->setSomme($mvmt->getSomme());
                $mvmtLycee->setDate($mvmt->getDate());
                /**
                 * @var $soldeLycee SoldeLycee
                 */
                $soldeLycee = $this->getDoctrine()->getRepository('MascaTresorBundle:SoldeLycee')->find(1);
                $soldeLycee->setDate($mvmtLycee->getDate());

                $mvmtLycee->setSoldePrecedent($soldeLycee->getSolde());
                $soldeLycee->setSolde($soldeLycee->getSolde() + $mvmtLycee->getSomme());
                $mvmtLycee->setSoldeApres($soldeLycee->getSolde());
                $em->persist($mvmtLycee);

                $em->flush();

            } catch (ConstraintViolationException $e) {
                return $this->render('MascaTresorBundle:Universite:formulaire_operation.html.twig',[
                    'form'=>$form->createView(),
                    'error_message' => 'Une erreur d\'enregistrement vient de se produire, veuillez reesseyez svp! '
                ]);
            }
            return $this->redirect($this->generateUrl('home_caisse_universite'));
        }
        return $this->render('MascaTresorBundle:Universite:formulaire_operation.html.twig',[
            'form'=>$form->createView()
        ]);
    }
}