<?php

namespace Masca\PersonnelBundle\Controller;

use Masca\PersonnelBundle\Entity\Employer;
use Masca\PersonnelBundle\Entity\InfoSalaireFixe;
use Masca\PersonnelBundle\Entity\InfoVolumeHoraire;
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
        $nbParPage = 30;
        /**
         * @var $repository EmployerRepository
         */
        $repository = $this->getDoctrine()->getManager()
            ->getRepository('MascaPersonnelBundle:Employer');
        /**
         * @var $listEmployer Employer[]
         */
        $listEmployer = $repository->getEmployers($nbParPage,$page);

        if($request->getMethod() == 'POST') {
            $listEmployer = $repository->findEmployer($nbParPage,$page,$request->get('key_word'));
        }
        return $this->render('MascaPersonnelBundle:Default:index.html.twig', [
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

        return $this->render('MascaPersonnelBundle:Default:detail.html.twig',[
            'employer'=>$employer,
            'posteFixes'=>$postFixes,
            'posteHoraires'=>$postHoraires
        ]);
    }
}
