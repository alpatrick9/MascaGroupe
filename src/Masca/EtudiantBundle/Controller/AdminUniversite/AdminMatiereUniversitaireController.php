<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/20/16
 * Time: 2:00 PM
 */

namespace Masca\EtudiantBundle\Controller\AdminUniversite;


use Doctrine\DBAL\Exception\ConstraintViolationException;
use Masca\EtudiantBundle\Entity\Matiere;
use Masca\EtudiantBundle\Entity\MatiereRepository;
use Masca\EtudiantBundle\Type\MatiereUniversitaireType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class AdminMatiereUniversitaireController extends Controller
{
    /**
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/universite/admin/list/matieres/{page}", name="list_matieres_universite", defaults={"page" = 1})
     */
    public function listMatierAction($page) {
        $nbParPage = 30;
        /**
         * @var $repository MatiereRepository
         */
        $repository = $this->getDoctrine()->getManager()
            ->getRepository('MascaEtudiantBundle:Matiere');
        /**
         * @var $matieres Matiere[]
         */
        $matieres = $repository->getMatieres($nbParPage,$page);

        return $this->render('MascaEtudiantBundle:Admin_universite:list-matieres.html.twig',array(
            'matieres'=>$matieres,
            'page'=> $page,
            'nbPage' => ceil(count($matieres)/$nbParPage)));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/universite/admin/ajouter/matiere/", name="ajouter_matiere_universite")
     */
    public function ajouterMatiereAction(Request $request) {
        $matiere = new Matiere();
        $form = $this->createForm(MatiereUniversitaireType::class, $matiere);

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($matiere);
                $em->flush();
            } catch (ConstraintViolationException $e) {
                return $this->render('MascaEtudiantBundle:Admin_universite:formulaire-matieres.html.twig',[
                    'form'=>$form->createView(),
                    'error_message'=>'la matiere '.$matiere->getIntitule().' existe déjà'
                ]);
            }
            return $this->redirect($this->generateUrl('list_matieres_universite'));
        }
        return $this->render('MascaEtudiantBundle:Admin_universite:formulaire-matieres.html.twig',[
            'form'=>$form->createView()
        ]);
    }
    
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/universite/admin/modifier/matiere/{matiere_id}", name="modifier_matiere_universite")
     * @ParamConverter("matiere", options={"mapping": {"matiere_id":"id"}})
     */
    public function modifierMatiereAction(Request $request, Matiere $matiere) {
        $form = $this->createForm(MatiereUniversitaireType::class, $matiere);

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
             try {
                 $this->getDoctrine()->getManager()->flush();
             } catch (ConstraintViolationException $e) {
                 return $this->render('MascaEtudiantBundle:Admin_universite:formulaire-matieres.html.twig',[
                     'form'=>$form->createView(),
                     'error_message'=>'la matiere '.$matiere->getIntitule().' existe déjà'
                 ]);
             }
            return $this->redirect($this->generateUrl('list_matieres_universite'));
        }

        return $this->render('MascaEtudiantBundle:Admin_universite:formulaire-matieres.html.twig',[
            'form'=>$form->createView()
        ]);
    }
}