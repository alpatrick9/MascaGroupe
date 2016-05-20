<?php
/**
 * Created by PhpStorm.
 * User: patmi
 * Date: 16/05/2016
 * Time: 20:35
 */

namespace Masca\EtudiantBundle\Controller\AdminUniversite;


use Masca\EtudiantBundle\Entity\GrilleFraisScolariteUniversite;
use Masca\EtudiantBundle\Type\GrilleEcolageUniversiteType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class AdminEcolageUniversiteController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/universite/admin/grille-ecolage/", name="grille_ecolage_universite")
     */
    public function grilleFraisScolariteAction() {
        return $this->render('MascaEtudiantBundle:Admin_universite:grilles-frais-scolarite.html.twig',[
            'grilles'=>$this->getDoctrine()->getManager()->getRepository('MascaEtudiantBundle:GrilleFraisScolariteUniversite')->findAll()
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/universite/admin/ajouter-grille-ecolage/", name="ajouter_grille_ecolage_universite")
     */
    public function ajouterGrilleAction(Request $request) {
        $grille = new GrilleFraisScolariteUniversite();
        $form = $this->createForm(GrilleEcolageUniversiteType::class, $grille);
        
        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if(!$this->getDoctrine()->getManager()->
            getRepository('MascaEtudiantBundle:GrilleFraisScolariteUniversite')->isValid($grille->getFiliere(),$grille->getNiveauEtude())) {
                $js = '<script  type="text/javascript">'.
                    'document.getElementById("DivInfo").style.display = "block";'.
                    '</script>';
                return $this->render('formulaire-grilles-frais-scolarite.html.twig', [
                    'form'=>$form->createView(),
                    'js'=>$js
                ]);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($grille);
            $em->flush();
            return $this->redirect($this->generateUrl('grille_ecolage_universite'));
        }
        return $this->render('MascaEtudiantBundle:Admin_universite:formulaire-grilles-frais-scolarite.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/universite/admin/modifier-grille-ecolage/{grille_id}", name="modifier_grille_ecolage_universite")
     * @ParamConverter("grilleFraisScolariteUniversite", options={"mapping": {"grille_id":"id"}})
     */
    public function modifierGrilleAction(Request $request, GrilleFraisScolariteUniversite $grilleFraisScolariteUniversite) {
        $form = $this->createForm(GrilleEcolageUniversiteType::class, $grilleFraisScolariteUniversite);

        $options = $form->get('filiere')->getConfig()->getOptions();
        $options['disabled'] = true;
        $form->add('filiere',EntityType::class,$options);

        $options = $form->get('niveauEtude')->getConfig()->getOptions();
        $options['disabled'] = true;
        $form->add('niveauEtude',EntityType::class,$options);

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirect($this->generateUrl('grille_ecolage_universite'));
        }
        return $this->render('MascaEtudiantBundle:Admin_universite:formulaire-grilles-frais-scolarite.html.twig',[
            'form'=>$form->createView()
        ]);
    }
}