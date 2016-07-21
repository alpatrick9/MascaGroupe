<?php
/**
 * Created by PhpStorm.
 * User: patmi
 * Date: 21/04/2016
 * Time: 10:13
 */

namespace Masca\EtudiantBundle\Controller\Lycee;

use Masca\EtudiantBundle\Entity\FraisScolariteLyceen;
use Masca\EtudiantBundle\Entity\FraisScolariteLyceenRepository;
use Masca\EtudiantBundle\Entity\InfoEtudiant;
use Masca\EtudiantBundle\Entity\Lyceen;
use Masca\EtudiantBundle\Entity\LyceenRepository;
use Masca\EtudiantBundle\Entity\Person;
use Masca\EtudiantBundle\Type\InfoEtudiantType;
use Masca\EtudiantBundle\Type\LyceenType;
use Masca\EtudiantBundle\Type\PersonType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class LyceeController
 * @package Masca\EtudiantBundle\Controller\Lycee
 * @Route("/lycee")
 */
class LyceeController extends Controller
{
    /**
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/accueil/{page}", name="accueil_lycee", defaults={"page" = 1})
     */
    public function indexAction($page, Request $request) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_USER')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $nbParPage = 30;
        /**
         * @var $repository LyceenRepository
         */
        $repository = $this->getDoctrine()->getManager()
                            ->getRepository('MascaEtudiantBundle:Lyceen');
        /**
         * @var $lyceens Lyceen[]
         */
        $lyceens = $repository->getLyceens($nbParPage,$page);

        if($request->getMethod() == 'POST') {
            $lyceens = $repository->findLyceens($nbParPage,$page,$request->get('key_word'));
        }
        return $this->render('MascaEtudiantBundle:Lycee:index.html.twig',array(
            'lyceens'=>$lyceens,
            'page'=> $page,
            'nbPage' => ceil(count($lyceens)/$nbParPage)
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/inscription/", name="inscription_lycee")
     */
    public function inscriptionAction(Request $request) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_ECONOMAT')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $person = new Person();
        $personForm = $this->createForm(PersonType::class,$person);
        $personForm
            ->remove('numCin')
            ->remove('dateDelivranceCin')
            ->remove('lieuDelivranceCin');

        $infoEtudiant = new InfoEtudiant();
        $infoEtudiantForm = $this->createForm(InfoEtudiantType::class,$infoEtudiant);

        $lyceen = new Lyceen();
        $lyceenForm = $this->createForm(LyceenType::class,$lyceen);

        if($request->getMethod() == 'POST') {
            $personForm->handleRequest($request);
            $infoEtudiantForm->handleRequest($request);
            $lyceenForm->handleRequest($request);

            $lyceen->setPerson($person);
            $lyceen->setInfoEtudiant($infoEtudiant);
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($lyceen);
                $em->flush();
            } catch (ConstraintViolationException $e) {
                return $this->render('MascaEtudiantBundle:Lycee:inscription.html.twig',array(
                    'personForm'=>$personForm->createView(),
                    'infoEtudiantForm'=>$infoEtudiantForm->createView(),
                    'etudeForm'=>$lyceenForm->createView(),
                    'error_message'=>'Le numero matricule '.$lyceen->getPerson()->getNumMatricule().' existe déjà! Veuillez le remplacer svp!'
                ));
            }

            return $this->redirect($this->generateUrl('accueil_lycee'));

        }
        return $this->render('MascaEtudiantBundle:Lycee:inscription.html.twig',array(
            'personForm'=>$personForm->createView(),
            'infoEtudiantForm'=>$infoEtudiantForm->createView(),
            'etudeForm'=>$lyceenForm->createView(),
        ));
    }

    /**
     * @param Lyceen $lyceen
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/details/{id}", name="details_lyceen")
     */
    public function detailsAction(Lyceen $lyceen, Request $request) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_USER')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        return $this->render('MascaEtudiantBundle:Lycee:details.html.twig',array(
            'lyceen'=>$lyceen
        ));
    }

    /**
     * @param Request $request
     * @param Lyceen $lyceen
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/modifier/{id}", name="modifier_lyceen")
     */
    public function modifierAction(Request $request, Lyceen $lyceen) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_ECONOMAT')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $personForm = $this->createForm(PersonType::class,$lyceen->getPerson());
        $personForm
            ->remove('numCin')
            ->remove('dateDelivranceCin')
            ->remove('lieuDelivranceCin');

        $infoEtudiantForm = $this->createForm(InfoEtudiantType::class,$lyceen->getInfoEtudiant());

        /**
         * @var $ecolageRepository FraisScolariteLyceenRepository
         */
        $ecolageRepository = $this->getDoctrine()->getManager()->getRepository('MascaEtudiantBundle:FraisScolariteLyceen');

        if($ecolageRepository->statusEcolage($lyceen)) {
            $reductionField = $infoEtudiantForm->get('reduction');
            $options = $reductionField->getConfig()->getOptions();
            $options['disabled']=true;
            $infoEtudiantForm->add('reduction',NumberType::class,$options);
        }

        if($request->getMethod() == 'POST') {
            $personForm->handleRequest($request);
            $infoEtudiantForm->handleRequest($request);

            $this->getDoctrine()->getManager()->flush();
            return $this->redirect($this->generateUrl('details_lyceen',array('id'=>$lyceen->getId())));

        }

        return $this->render('MascaEtudiantBundle:Lycee:modifier.html.twig',array(
            'personForm'=>$personForm->createView(),
            'infoEtudiantForm'=>$infoEtudiantForm->createView(),
            'lyceenId'=>$lyceen->getId()
        ));
    }

    /**
     * @param Request $request
     * @param Lyceen $lyceen
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/reinscription/{id}", name="reinscription_lyceen")
     */
    public function reinscriptionAction(Request $request, Lyceen $lyceen) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_ECONOMAT')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $lyceenForm = $this->createForm(LyceenType::class, $lyceen);
        /**
         * @var $ecolageRepository FraisScolariteLyceenRepository
         */
        $ecolageRepository = $this->getDoctrine()->getManager()->getRepository('MascaEtudiantBundle:FraisScolariteLyceen');

        if($ecolageRepository->statusEcolage($lyceen)) {
            return $this->render('MascaEtudiantBundle:Lycee:details.html.twig',array(
                'lyceen'=>$lyceen,
                'error_message'=>'Reinscription refuser. Il reste encore des frais de scolarités qui ne sont pas encore regularisés pour l\'année scolaire '.$lyceen->getAnneeScolaire()
            ));
        }

        if($request->getMethod() == 'POST') {
            $lyceenForm->handleRequest($request);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirect($this->generateUrl('details_lyceen',array('id'=>$lyceen->getId())));
        }

        return $this->render('MascaEtudiantBundle:Lycee:reinscription.html.twig',array(
            'lyceenForm'=>$lyceenForm->createView(),
            'lyceen'=>$lyceen
        ));
    }

    /**
     * @param Request $request
     * @param Lyceen $lyceen
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/supprimer/{id}", name="supprimer_lyceen")
     */
    public function deleteLyceenAction(Request $request, Lyceen $lyceen) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_ECONOMAT')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($lyceen);
        $em->flush();
        return $this->redirect($this->generateUrl('accueil_lycee'));
    }

    /**
     * @param Request $request
     * @param $page
     * @return Response
     * @Route("/print/{page}", name="print_list_lyceen")
     */
    public function printNoteAction(Request $request, $page) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_ECONOMAT')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }

        $html = $this->forward('MascaEtudiantBundle:Lycee/Impression/LyceeImpression:listLyceePrint',[
            'page'=>$page
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