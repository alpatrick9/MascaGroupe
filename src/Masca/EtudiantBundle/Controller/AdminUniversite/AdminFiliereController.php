<?php
/**
 * Created by PhpStorm.
 * User: patmi
 * Date: 06/05/2016
 * Time: 10:17
 */

namespace Masca\EtudiantBundle\Controller\AdminUniversite;


use Masca\EtudiantBundle\Entity\Filiere;
use Masca\EtudiantBundle\Entity\FiliereRepository;
use Masca\EtudiantBundle\Entity\GrilleFraisScolariteUniversite;
use Masca\EtudiantBundle\Entity\NiveauEtude;
use Masca\EtudiantBundle\Entity\Semestre;
use Masca\EtudiantBundle\Type\FiliereType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AdminFiliereController extends Controller
{
    /**
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/universite/admin/{page}", name="admin_univ_filiere", defaults={"page"=1})
     */
    public function indexAction($page) {
        $nbParPage = 30;
        /**
         * @var $repository FiliereRepository
         */
        $repository = $this->getDoctrine()->getManager()
            ->getRepository('MascaEtudiantBundle:Filiere');
        /**
         * @var $filieres Filiere[]
         */
        $filieres = $repository->getFiliers($nbParPage,$page);

        return $this->render('MascaEtudiantBundle:Admin_universite:index.html.twig',array(
            'filieres'=>$filieres,
            'page'=> $page,
            'nbPage' => ceil(count($filieres)/$nbParPage)));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/universite/admin/ajoute-filiere/", name="ajouter_filiere_univ")
     */
    public function ajouterFiliereAction(Request $request) {
        $filiere = new Filiere();
        $filiereForm = $this->createForm(FiliereType::class, $filiere);

        if($request->getMethod() == 'POST') {
            $filiereForm->handleRequest($request);
            $em = $this->getDoctrine()->getManager();
            $em->persist($filiere);
            $em->flush();
            return $this->redirect($this->generateUrl('admin_univ_filiere'));
        }
        return $this->render('MascaEtudiantBundle:Admin_universite:ajouter-filiere.html.twig',[
            'filiereForm'=>$filiereForm->createView()
        ]);
    }

}