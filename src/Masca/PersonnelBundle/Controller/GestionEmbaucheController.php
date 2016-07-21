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
use Masca\EtudiantBundle\Type\PersonType;
use Masca\PersonnelBundle\Entity\Employer;
use Masca\PersonnelBundle\Entity\Status;
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
        
        if($request->getMethod() == 'POST') {
            $personForm->handleRequest($request);
            $statusForm->handleRequest($request);

            /**
             * @var $personRepository PersonRepository
             */
            $personRepository = $this->getDoctrine()->getManager()->getRepository('MascaEtudiantBundle:Person');
            if($personRepository->doublantMatricule($person)) {
                return $this->render('MascaPersonnelBundle:Embauche:formulaire-recrutement.html.twig',[
                    'form_person'=>$personForm->createView(),
                    'form_status'=>$statusForm->createView(),
                    'error_message'=>'Le numero matricule '.$person->getNumMatricule().' existe déjà! Veuillez le remplacer svp!'
                ]);
            }

            $employer = new Employer();
            $employer->setPerson($person);
            
            $status->setEmployer($employer);
        }
        
        return $this->render('MascaPersonnelBundle:Embauche:formulaire-recrutement.html.twig',[
            'form_person'=>$personForm->createView(),
            'form_status'=>$statusForm->createView()
        ]);
    }
}