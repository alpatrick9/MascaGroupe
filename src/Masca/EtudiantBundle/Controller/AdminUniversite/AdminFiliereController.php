<?php
/**
 * Created by PhpStorm.
 * User: patmi
 * Date: 06/05/2016
 * Time: 10:17
 */

namespace Masca\EtudiantBundle\Controller\AdminUniversite;


use Doctrine\DBAL\Exception\ConstraintViolationException;
use Masca\EtudiantBundle\Entity\Filiere;
use Masca\EtudiantBundle\Entity\FiliereRepository;
use Masca\EtudiantBundle\Type\FiliereType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
        $form = $this->createForm(FiliereType::class, $filiere);

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($filiere);
                $em->flush();
            } catch (ConstraintViolationException $e) {
                return $this->render('MascaEtudiantBundle:Admin_universite:formulaire-filiere.html.twig',[
                    'form'=>$form->createView(),
                    'error_message'=>'Le filiere '.$filiere->getIntitule().' existe déjà'
                ]);
            }

            return $this->redirect($this->generateUrl('admin_univ_filiere'));
        }
        return $this->render('MascaEtudiantBundle:Admin_universite:formulaire-filiere.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/universite/admin/modifier-filiere/{filiere_id}", name="modifier_filiere_univ")
     * @ParamConverter("filiere", options={"mapping": {"filiere_id":"id"}})
     */
    public function modifierFiliereAction(Request $request, Filiere $filiere) {
        $form = $this->createForm(FiliereType::class, $filiere);

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            try {
                $this->getDoctrine()->getManager()->flush();
            } catch (ConstraintViolationException $e) {
                return $this->render('MascaEtudiantBundle:Admin_universite:formulaire-filiere.html.twig',[
                    'form'=>$form->createView(),
                    'error_message'=>'Le filiere '.$filiere->getIntitule().' existe déjà'
                ]);
            }
            return $this->redirect($this->generateUrl('admin_univ_filiere'));
        }
        return $this->render('MascaEtudiantBundle:Admin_universite:formulaire-filiere.html.twig',[
            'form'=>$form->createView()
        ]);
    }

}