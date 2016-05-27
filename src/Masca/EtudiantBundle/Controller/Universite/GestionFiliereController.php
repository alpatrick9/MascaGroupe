<?php
/**
 * Created by PhpStorm.
 * User: patmi
 * Date: 16/05/2016
 * Time: 20:48
 */

namespace Masca\EtudiantBundle\Controller\Universite;


use Masca\EtudiantBundle\Entity\UniversitaireSonFiliere;
use Masca\EtudiantBundle\Type\SonFiliereType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class GestionFiliereController extends Controller
{
    /**
     * @param Request $request
     * @param UniversitaireSonFiliere $sonFiliere
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/universite/reinscription/{id}", name="reinscription_universite")
     */
    public function reinscriptionAction(Request $request, UniversitaireSonFiliere $sonFiliere) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_SECRETAIRE')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $sonFiliereForm = $this->createForm(SonFiliereType::class, $sonFiliere);

        if($request->getMethod() == 'POST') {
            $sonFiliereForm->handleRequest($request);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirect($this->generateUrl('details_etude_universitaire', array('sonFiliere_id'=>$sonFiliere->getId())));
        }

        return $this->render('MascaEtudiantBundle:Universite:reinscription.html.twig',array(
            'sonFiliereForm'=>$sonFiliereForm->createView(),
            'fullEtudantName'=> $sonFiliere->getUniversitaire()->getPerson()->getPrenom() ." ".$sonFiliere->getUniversitaire()->getPerson()->getNom(),
            'universitaireId'=>$sonFiliere->getUniversitaire()->getId(),
            'sonFiliereId'=> $sonFiliere->getId()
        ));
    }

    /**
     * @param UniversitaireSonFiliere $sonFiliere
     * @return \Symfony\Component\HttpFoundation\Response
     * @ParamConverter("sonFiliere", options={"mapping": {"sonFiliere_id":"id"}})
     * @Route("/universite/details-etude/{sonFiliere_id}", name="details_etude_universitaire")
     */
    public function detailsEtudeAction(UniversitaireSonFiliere $sonFiliere) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_SECRETAIRE')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        return $this->render('MascaEtudiantBundle:Universite:details-etude.html.twig',[
            'sonFiliere'=>$sonFiliere
        ]);
    }
}