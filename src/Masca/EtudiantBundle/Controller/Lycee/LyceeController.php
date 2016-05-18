<?php
/**
 * Created by PhpStorm.
 * User: patmi
 * Date: 21/04/2016
 * Time: 10:13
 */

namespace Masca\EtudiantBundle\Controller\Lycee;

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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class LyceeController extends Controller
{
    /**
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/accueil/lycee/{page}", name="accueil_lycee", defaults={"page" = 1})
     */
    public function indexAction($page) {
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

        return $this->render('MascaEtudiantBundle:Lycee:index.html.twig',array(
            'lyceens'=>$lyceens,
            'page'=> $page,
            'nbPage' => ceil(count($lyceens)/$nbParPage)
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/lycee/inscription/", name="inscription_lycee")
     */
    public function inscriptionAction(Request $request) {
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

            $em = $this->getDoctrine()->getManager();
            $em->persist($lyceen);
            $em->flush();
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
     * @Route("/lycee/details/{id}", name="details_lyceen")
     */
    public function detailsAction(Lyceen $lyceen) {
        return $this->render('MascaEtudiantBundle:Lycee:details.html.twig',array(
            'lyceen'=>$lyceen
        ));
    }

    /**
     * @param Request $request
     * @param Lyceen $lyceen
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/lycee/modifier/{id}", name="modifier_lyceen")
     */
    public function modifierAction(Request $request, Lyceen $lyceen) {
        $personForm = $this->createForm(PersonType::class,$lyceen->getPerson());
        $personForm
            ->remove('numCin')
            ->remove('dateDelivranceCin')
            ->remove('lieuDelivranceCin');

        $infoEtudiantForm = $this->createForm(InfoEtudiantType::class,$lyceen->getInfoEtudiant());

        $lyceenForm = $this->createForm(LyceenType::class,$lyceen);

        if($request->getMethod() == 'POST') {
            $personForm->handleRequest($request);
            $infoEtudiantForm->handleRequest($request);
            $lyceenForm->handleRequest($request);

            $this->getDoctrine()->getManager()->flush();
            return $this->redirect($this->generateUrl('details_lyceen',array('id'=>$lyceen->getId())));

        }

        return $this->render('MascaEtudiantBundle:Lycee:modifier.html.twig',array(
            'personForm'=>$personForm->createView(),
            'infoEtudiantForm'=>$infoEtudiantForm->createView(),
            'etudeForm'=>$lyceenForm->createView(),
            'lyceenId'=>$lyceen->getId()
        ));
    }

    /**
     * @param Request $request
     * @param Lyceen $lyceen
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/lycee/reinscription/{id}", name="reinscription_lyceen")
     */
    public function reinscriptionAction(Request $request, Lyceen $lyceen) {

        $lyceenForm = $this->createForm(LyceenType::class, $lyceen);

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
}