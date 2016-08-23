<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 8/22/16
 * Time: 10:52 AM
 */

namespace Masca\PersonnelBundle\Controller;
use Doctrine\DBAL\Exception\ConstraintViolationException;
use Masca\EtudiantBundle\Entity\Person;
use Masca\PersonnelBundle\Entity\Employer;
use Masca\PersonnelBundle\Entity\InfoSalaireFixe;
use Masca\PersonnelBundle\Entity\InfoVolumeHoraire;
use Masca\PersonnelBundle\Entity\MatiereLyceeEnseigner;
use Masca\PersonnelBundle\Entity\MatiereUnivEnseigner;
use Masca\PersonnelBundle\Entity\Status;
use Masca\PersonnelBundle\Type\EmployerType;
use Masca\PersonnelBundle\Type\MatiereLyceeEnseignerType;
use Masca\PersonnelBundle\Type\MatiereUnivEnseingerType;
use Masca\PersonnelBundle\Type\StatusType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class GestionPostController
 * @package Masca\PersonnelBundle\Controller
 * @Route("/poste")
 */
class GestionPostController extends Controller
{
    /**
     * @param Request $request
     * @param Person $person
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/add/post/{id}", name="add_post")
     */
    public function addPostAction(Request $request, Person $person) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')) {
            return $this->render("::message-layout.html.twig", [
                'message' => 'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink' => $request->headers->get('referer')
            ]);
        }
        $status = new Status();
        $statusForm = $this->createForm(StatusType::class, $status);

        $employer = $this->getDoctrine()->getManager()->getRepository('MascaPersonnelBundle:Employer')->findOneBy(["person"=>$person->getId()]);
        $employerForm = $this->createForm(EmployerType::class, $employer);

        $employer->setPerson($person);

        $status->setEmployer($employer);

        if($request->getMethod() == 'POST') {
            $statusForm->handleRequest($request);
            $employerForm->handleRequest($request);
            if($status->getTypeSalaire() == "fixe" && $status->getTypePoste() == "prof") {
                return $this->render('MascaPersonnelBundle:Embauche:formulaire-recrutement.html.twig',[
                    'form_status'=>$statusForm->createView(),
                    'form_employer'=>$employerForm->createView(),
                    'error_message'=>'Vous avez choisissez un poste d\'enseignant alors le mode de payement devrais être en volume horaire'
                ]);
            }

            $session = $this->get('session');

            $session->set('status', serialize($status));

            return $this->redirect($this->generateUrl('details_embauche'));
        }
        return $this->render('MascaPersonnelBundle:GestionPost:formulaire-add-post.html.twig',[
            'employer'=>$employer,
            'form_status'=>$statusForm->createView(),
            'form_employer'=>$employerForm->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param InfoVolumeHoraire $infoVolumeHoraire
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/add-matiere/{id}", name="add_matiere_enseigner")
     */
    public function addMatiere(Request $request,InfoVolumeHoraire $infoVolumeHoraire) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')) {
            return $this->render("::message-layout.html.twig", [
                'message' => 'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink' => $request->headers->get('referer')
            ]);
        }

        $form = null;
        $matiere = null;
        switch ($infoVolumeHoraire->getStatus()->getEtablisement()) {
            case 'universite':
                $matiere = new MatiereUnivEnseigner();
                $form = $this->createForm(new MatiereUnivEnseingerType(),$matiere);
                break;
            case 'lycee':
                $matiere = new MatiereLyceeEnseigner();
                $form = $this->createForm(new MatiereLyceeEnseignerType(), $matiere);
                break;
        }

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $matiere->setInfo($infoVolumeHoraire);
            $em = $this->getDoctrine()->getManager();
            try {
                $em->persist($matiere);
                $em->flush();
            } catch (ConstraintViolationException $e) {
                return $this->render('MascaPersonnelBundle:GestionPost:formulaire-add-matiere.html.twig', [
                        'form'=> $form->createView(),
                        'info'=> $infoVolumeHoraire,
                        'error_message'=> 'Le matière '.$matiere->getMatiere()->getIntitule().' est déjà enseigné par '.$infoVolumeHoraire->getStatus()->getEmployer()->getPerson()->getNom().' '.$infoVolumeHoraire->getStatus()->getEmployer()->getPerson()->getPrenom()
                    ]
                );
            }
            return $this->redirect($this->generateUrl('details', ['id'=>$infoVolumeHoraire->getStatus()->getEmployer()->getId()]));
        }

        return $this->render('MascaPersonnelBundle:GestionPost:formulaire-add-matiere.html.twig', [
            'form'=> $form->createView(),
            'info'=> $infoVolumeHoraire
        ]);
    }

    /**
     * @param Request $request
     * @param MatiereUnivEnseigner $matiereUnivEnseigner
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/matiere-univ/delete/{id}", name="delete_matiere_univ_enseigner")
     */
    public function deleteMatiereUnivEnseignerAction(Request $request, MatiereUnivEnseigner $matiereUnivEnseigner) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')) {
            return $this->render("::message-layout.html.twig", [
                'message' => 'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink' => $request->headers->get('referer')
            ]);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($matiereUnivEnseigner);
        $em->flush();
        return $this->redirect($this->generateUrl('details', ['id'=>$matiereUnivEnseigner->getInfo()->getStatus()->getEmployer()->getId()]));
    }

    /**
     * @param Request $request
     * @param MatiereLyceeEnseigner $matiereLyceeEnseigner
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response$
     * @Route("/matiere-lycee/delele/{id}",name="delete_matiere_lycee_enseigner")
     */
    public function deleteMatiereLyceeEnseignerAction(Request $request, MatiereLyceeEnseigner $matiereLyceeEnseigner) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')) {
            return $this->render("::message-layout.html.twig", [
                'message' => 'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink' => $request->headers->get('referer')
            ]);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($matiereLyceeEnseigner);
        $em->flush();
        return $this->redirect($this->generateUrl('details', ['id'=>$matiereLyceeEnseigner->getInfo()->getStatus()->getEmployer()->getId()]));
    }

    /**
     * @param Request $request
     * @param InfoVolumeHoraire $infoVolumeHoraire
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/post-horaire/delete/{id}", name="delete_poste_horaire")
     */
    public function deletePosteHoraireAction(Request $request, InfoVolumeHoraire $infoVolumeHoraire) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')) {
            return $this->render("::message-layout.html.twig", [
                'message' => 'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink' => $request->headers->get('referer')
            ]);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($infoVolumeHoraire);
        $em->flush();
        return $this->redirect($this->generateUrl('details', ['id'=>$infoVolumeHoraire->getStatus()->getEmployer()->getId()]));
    }

    /**
     * @param Request $request
     * @param InfoSalaireFixe $salaireFixe
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/post-fixe/delete/{id}", name="delete_poste_fixe")
     */
    public function deletePosteFixeAction(Request $request, InfoSalaireFixe $salaireFixe) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')) {
            return $this->render("::message-layout.html.twig", [
                'message' => 'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink' => $request->headers->get('referer')
            ]);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($salaireFixe);
        $em->flush();
        return $this->redirect($this->generateUrl('details', ['id'=>$salaireFixe->getStatus()->getEmployer()->getId()]));
    }
}