<?php

namespace Masca\PersonnelBundle\Controller;

use Masca\EtudiantBundle\Entity\Person;
use Masca\EtudiantBundle\Type\PersonType;
use Masca\PersonnelBundle\Type\StatusType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

/**
 * Class DefaultController
 * @package Masca\PersonnelBundle\Controller
 * @Route("/")
 */
class DefaultController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/accueil", name="personnel_home")
     */
    public function indexAction()
    {
        $person = new Person();
        $personnelForm = $this->createForm(PersonType::class, $person);
        $personnelForm->add('lesStatus',CollectionType::class,[
            'entry_type'=> 'Masca\PersonnelBundle\Type\StatusType',
            'allow_add' => true,
            'allow_delete' => true

        ]);
        return $this->render('MascaPersonnelBundle:Default:index.html.twig', [
            'form'=>$personnelForm->createView()
        ]);
    }
}
