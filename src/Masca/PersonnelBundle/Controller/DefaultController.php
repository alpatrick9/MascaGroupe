<?php

namespace Masca\PersonnelBundle\Controller;

use Masca\PersonnelBundle\Entity\Employer;
use Masca\PersonnelBundle\Entity\InfoSalaireFixe;
use Masca\PersonnelBundle\Entity\InfoVolumeHoraire;
use Masca\PersonnelBundle\Entity\MatiereLyceeEnseigner;
use Masca\PersonnelBundle\Entity\MatiereUnivEnseigner;
use Masca\PersonnelBundle\Entity\Status;
use Masca\PersonnelBundle\Repository\EmployerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DefaultController
 * @package Masca\PersonnelBundle\Controller
 * @Route("/")
 */
class DefaultController extends Controller
{
    /**
     * @param Request $request
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/accueil/{page}", name="personnel_home", defaults={"page" = 1})
     */
    public function indexAction(Request $request, $page)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')) {
            return $this->render("::message-layout.html.twig", [
                'message' => 'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink' => $request->headers->get('referer')
            ]);
        }
        
        return $this->render('MascaPersonnelBundle:Default:index.html.twig', [
            'page' => $page,
        ]);
    }

    /**
     * @param Request $request
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/list-emp/{page}/{keyword}", name="list_emp", defaults={"page" = 1, "keyword" = ""})
     */
    public function listEmployeAction(Request $request, $page, $keyword) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')) {
            return $this->render("::message-layout.html.twig", [
                'message' => 'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink' => $request->headers->get('referer')
            ]);
        }
        $nbParPage = 30;
        /**
         * @var $repository EmployerRepository
         */
        $repository = $this->getDoctrine()->getManager()
            ->getRepository('MascaPersonnelBundle:Employer');
        /**
         * @var $listEmployer Employer[]
         */
        $listEmployer = [];
        if($keyword == "") {
            $listEmployer = $repository->getEmployers($nbParPage,$page);
        } else {
            $listEmployer = $repository->findEmployer($nbParPage,$page,$keyword);
        }
        
        return $this->render('MascaPersonnelBundle:Default:list.html.twig', [
            'employers' => $listEmployer,
            'page' => $page,
            'nbPage' => ceil(count($listEmployer) / $nbParPage)
        ]);
    }

    /**
     * @param Request $request
     * @param Employer $employer
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/details/{id}", name="details")
     */
    public function detailPosteAction(Request $request, Employer $employer) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')) {
            return $this->render("::message-layout.html.twig", [
                'message' => 'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink' => $request->headers->get('referer')
            ]);
        }

        /**
         * @var $postFixes InfoSalaireFixe[]
         */
        $postFixes = $this->getDoctrine()->getManager()->getRepository('MascaPersonnelBundle:InfoSalaireFixe')->findAllPostFixe($employer);

        /**
         * @var $postHoraires InfoVolumeHoraire[]
         */
        $postHoraires = $this->getDoctrine()->getManager()->getRepository('MascaPersonnelBundle:InfoVolumeHoraire')->findAllPostHoraire($employer);

        /**
         * @var $matieresUnivEnseigner MatiereUnivEnseigner[]
         */
        $matieresUnivEnseigner = [];

        /**
         * @var $matieresLyceeEnseigner MatiereLyceeEnseigner[]
         */
        $matieresLyceeEnseigner = [];

        foreach ($postHoraires as $post) {
            $matieresUnivEnseigner[$post->getId()] = $this->getDoctrine()->getManager()->getRepository('MascaPersonnelBundle:MatiereUnivEnseigner')->findBy(['info'=>$post]);
            $matieresLyceeEnseigner[$post->getId()] = $this->getDoctrine()->getManager()->getRepository('MascaPersonnelBundle:MatiereLyceeEnseigner')->findBy(['info'=>$post]);
        }
        return $this->render('MascaPersonnelBundle:Default:detail.html.twig',[
            'employer'=>$employer,
            'posteFixes'=>$postFixes,
            'posteHoraires'=>$postHoraires,
            'matiereUniv'=>$matieresUnivEnseigner,
            'matiereLycee'=>$matieresLyceeEnseigner
        ]);
    }

    /**
     * @param Request $request
     * @param Employer $employer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/delete/{id}", name="delete_employer")
     */
    public function deleteEmployerAction(Request $request, Employer $employer) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')) {
            return $this->render("::message-layout.html.twig", [
                'message' => 'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink' => $request->headers->get('referer')
            ]);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($employer);
        $em->flush();
        return $this->redirect($this->generateUrl('personnel_home'));
    }
}
