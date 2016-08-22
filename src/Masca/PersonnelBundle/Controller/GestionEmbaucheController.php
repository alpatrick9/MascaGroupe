<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 7/21/16
 * Time: 10:02 AM
 */

namespace Masca\PersonnelBundle\Controller;


use Masca\EtudiantBundle\Entity\Person;
use Masca\EtudiantBundle\Entity\PersonRepository;
use Masca\EtudiantBundle\MascaEtudiantBundle;
use Masca\EtudiantBundle\Type\PersonType;
use Masca\PersonnelBundle\Entity\Employer;
use Masca\PersonnelBundle\Entity\InfoSalaireFixe;
use Masca\PersonnelBundle\Entity\InfoVolumeHoraire;
use Masca\PersonnelBundle\Entity\Status;
use Masca\PersonnelBundle\Type\EmployerType;
use Masca\PersonnelBundle\Type\InfoSalaireFixeType;
use Masca\PersonnelBundle\Type\InfoVolumeHoraireType;
use Masca\PersonnelBundle\Type\StatusType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class GestionEmbaucheController
 * @package Masca\PersonnelBundle\Controller
 * @Route("/embauche")
 */
class GestionEmbaucheController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/recrutement", name="recrutement")
     */
    public function createEmployerAction(Request $request) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')) {
            return $this->render("::message-layout.html.twig", [
                'message' => 'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink' => $request->headers->get('referer')
            ]);
        }
        
        $person = new Person();
        $personForm = $this->createForm(PersonType::class, $person);
        
        $status = new Status();
        $statusForm = $this->createForm(StatusType::class, $status);

        $employer = new Employer();
        $employerForm = $this->createForm(EmployerType::class, $employer);
        
        if($request->getMethod() == 'POST') {
            $personForm->handleRequest($request);
            $statusForm->handleRequest($request);
            $employerForm->handleRequest($request);

            /**
             * @var $personRepository PersonRepository
             */
            $personRepository = $this->getDoctrine()->getManager()->getRepository('MascaEtudiantBundle:Person');
            if($personRepository->doublantMatricule($person)) {
                return $this->render('MascaPersonnelBundle:Embauche:formulaire-recrutement.html.twig',[
                    'form_person'=>$personForm->createView(),
                    'form_status'=>$statusForm->createView(),
                    'form_employer'=>$employerForm->createView(),
                    'error_message'=>'Le numero matricule '.$person->getNumMatricule().' existe déjà! Veuillez le remplacer svp!'
                ]);
            }

            if($status->getTypeSalaire() == "fixe" && $status->getTypePoste() == "prof") {
                return $this->render('MascaPersonnelBundle:Embauche:formulaire-recrutement.html.twig',[
                    'form_person'=>$personForm->createView(),
                    'form_status'=>$statusForm->createView(),
                    'form_employer'=>$employerForm->createView(),
                    'error_message'=>'Vous avez choisissez un poste d\'enseignant alors le mode de payement devrais être en volume horaire'
                ]);
            }
            $employer->setPerson($person);
            
            $status->setEmployer($employer);

            $session = $this->get('session');

            $session->set('status', serialize($status));

            return $this->redirect($this->generateUrl('details_embauche'));
        }
        
        return $this->render('MascaPersonnelBundle:Embauche:formulaire-recrutement.html.twig',[
            'form_person'=>$personForm->createView(),
            'form_status'=>$statusForm->createView(),
            'form_employer'=>$employerForm->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/recrutement/detail", name="details_embauche")
     */
    public function detailEmbaucheAction(Request $request) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')) {
            return $this->render("::message-layout.html.twig", [
                'message' => 'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink' => $request->headers->get('referer')
            ]);
        }

        $session = $this->get('session');
        if(empty($session->get('status')))
            return $this->redirect($this->generateUrl('recrutement'));
        /**
         * @var $status Status
         */
        $status = unserialize($session->get('status'));
        switch ($status->getTypeSalaire()) {
            case 'fixe':
                $salaireFixe = new InfoSalaireFixe();
                $form = $this->createForm(InfoSalaireFixeType::class, $salaireFixe);
                break;
            case 'heure':
                $tauxHoraire = new InfoVolumeHoraire();
                $form = $this->createForm(InfoVolumeHoraireType::class, $tauxHoraire);
                break;
        }

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $em =  $this->getDoctrine()->getManager();
            switch($status->getTypeSalaire()) {
                case 'fixe':
                    $salaireFixe->setStatus($status);
                    if($status->getEmployer()->getPerson()->getId() != null) {
                        $status->setEmployer(
                            $this->getDoctrine()->getManager()->getRepository('MascaPersonnelBundle:Employer')->findOneBy(["person"=>$status->getEmployer()->getPerson()->getId()])
                        );
                    }
                    $em->persist($salaireFixe);
                    $em->flush();
                    $session->remove('status');
                    return $this->redirect($this->generateUrl('details',['id'=>$salaireFixe->getStatus()->getEmployer()->getId()]));
                    break;
                case 'heure':
                    $tauxHoraire->setStatus($status);
                    if($status->getEmployer()->getPerson()->getId() != null) {
                        $status->setEmployer(
                            $this->getDoctrine()->getManager()->getRepository('MascaPersonnelBundle:Employer')->findOneBy(["person"=>$status->getEmployer()->getPerson()->getId()])
                        );
                    }
                    $em->persist($tauxHoraire);
                    $em->flush();
                    return $this->redirect($this->generateUrl('details',['id'=>$tauxHoraire->getStatus()->getEmployer()->getId()]));
                    break;
            }


        }

        return $this->render('MascaPersonnelBundle:Embauche:formulaire-salaire.html.twig',[
            'form'=>$form->createView()
        ]);
    }
    
}