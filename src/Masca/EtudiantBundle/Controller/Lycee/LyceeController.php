<?php
/**
 * Created by PhpStorm.
 * User: patmi
 * Date: 21/04/2016
 * Time: 10:13
 */

namespace Masca\EtudiantBundle\Controller\Lycee;

use Masca\EtudiantBundle\Entity\InfoEtudiant;
use Masca\EtudiantBundle\Entity\Lyceen;
use Masca\EtudiantBundle\Entity\LyceenRepository;
use Masca\EtudiantBundle\Entity\Person;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class LyceeController extends Controller
{
    /**
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/accueil/lycee/{page}", name="accueil_lycee", defaults={"page" = 1})
     */
    public function indexAction($page) {
        $nbParPage = 30;
        /**
         * @var $repository LyceenRepository
         */
        $repository = $this->getDoctrine()->getManager()
                            ->getRepository('MascaEtudiantBundle:Lyceen');
        /**
         * @var $lyceens Lyceen[]
         */
        $lyceens = $repository->getLyceens($nbParPage,$page);

        return $this->render('MascaEtudiantBundle:Lycee:index.html.twig',array(
            'lyceens'=>$lyceens,
            'page'=> $page,
            'nbPage' => ceil(count($lyceens)/$nbParPage)
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/lycee/inscription/", name="inscription_lycee")
     */
    public function inscriptionAction(Request $request) {
        $person = new Person();
        $infoEtudiant = new InfoEtudiant();
        $lyceen = new Lyceen();

        $personFormBuilder = $this->createFormBuilder($person);
        $personFormBuilder
            ->add('numMatricule',TextType::class,array(
                'label'=>'Numeros matricule'
            ))
            ->add('nom',TextType::class,array(
                'label'=>'Nom'
            ))
            ->add('prenom',TextType::class,array(
                'label'=>'Prénom',
                'required'=>false
            ))
            ->add('dateNaissance',DateType::class,array(
                'label'=>'Date de naissance',
                'format'=>'dd MMMM yyyy',
                'years'=>range(date('Y')-50,date('Y')),
                'placeholder'=>array('year'=>'Année','day'=>'Jour','month'=>'Mois')

            ))
            ->add('lieuNaissance',TextType::class ,array(
                'label'=>'Lieu de Naissance'
            ));
        $personForm = $personFormBuilder->getForm();

        $infoEtudiantFormBuilder = $this->createFormBuilder($infoEtudiant);
        $infoEtudiantFormBuilder
            ->add('adresse',TextType::class,array(
                'label'=>'Adresse'
            ))
            ->add('tel',TextType::class,array(
                'label'=>'Téléphone',
                'required'=>false
            ))
            ->add('email',EmailType::class,array(
                'label'=>'E-mail',
                'required'=>false
            ))
            ->add('nomMere',TextType::class,array(
                'label'=>'Nom de votre mère'
            ))
            ->add('nomPere',TextType::class,array(
                'label'=>'Nom de votre père'
            ))
            ->add('telParent',TextType::class,array(
                'label'=>'Contact de votre parent',
                'required'=>false
            ))
            ->add('emailParent',EmailType::class,array(
                'label'=>'E-mail de votre parent',
                'required'=>false
            ))
            ->add('nomTuteur',TextType::class,array(
                'label'=>'Nom de votre tuteur',
                'required'=>false
            ));
        $infoEtudiantForm = $infoEtudiantFormBuilder->getForm();

        $lyceenFormBuilder = $this->createFormBuilder($lyceen);
        $lyceenFormBuilder
            ->add('dateReinscription',DateType::class,array(
                'label'=>'Date de réinscription',
                'format'=>'dd MMMM yyyy',
                'years'=>range(date('Y')-1,date('Y')+5),
                'placeholder'=>array('year'=>'Année','day'=>'Jour','month'=>'Mois')
            ))
            ->add('numeros',IntegerType::class,array(
                'label'=>'Numéros en classe',
                'required'=>true,
                'attr'=>array('min'=>1)
            ))
            ->add('anneeScolaire',TextType::class,array(
                'label'=>'Année scolaire',
                'required'=>true,
                'attr'=>array('placeholder'=>'2015-2016')
            ))
            ->add('sonClasse',EntityType::class,array(
                'label'=>'Classe',
                'class'=>'Masca\EtudiantBundle\Entity\Classe',
                'choice_label'=>'intitule',
                'required'=>true,
                'placeholder'=>'choississez ...',
                'empty_data'=>null
            ));
        $lyceenForm = $lyceenFormBuilder->getForm();

        if($request->getMethod() == 'POST') {
            $personForm->handleRequest($request);
            $infoEtudiantForm->handleRequest($request);
            $lyceenForm->handleRequest($request);

            $lyceen->setPerson($person);
            $lyceen->setInfoEtudiant($infoEtudiant);

            $em = $this->getDoctrine()->getManager();
            $em->persist($lyceen);
            $em->flush();
            return $this->redirect($this->generateUrl('accueil_lycee'));

        }
        return $this->render('MascaEtudiantBundle:Lycee:inscription.html.twig',array(
            'personForm'=>$personForm->createView(),
            'infoEtudiantForm'=>$infoEtudiantForm->createView(),
            'etudeForm'=>$lyceenForm->createView(),
        ));
    }

    /**
     * @param Lyceen $lyceen
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/lycee/details/{id}", name="details_lyceen")
     */
    public function detailsAction(Lyceen $lyceen) {
        return $this->render('MascaEtudiantBundle:Lycee:details.html.twig',array(
            'lyceen'=>$lyceen
        ));
    }

    /**
     * @param Request $request
     * @param Lyceen $lyceen
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/lycee/modifier/{id}", name="modifier_lyceen")
     */
    public function modifierAction(Request $request, Lyceen $lyceen) {
        $personFormBuilder = $this->createFormBuilder($lyceen->getPerson());
        $personFormBuilder
            ->add('numMatricule',TextType::class,array(
                'label'=>'Numeros matricule',
                'disabled'=>true,
                'data'=>$lyceen->getPerson()->getNumMatricule()
            ))
            ->add('nom',TextType::class,array(
                'label'=>'Nom',
                'disabled'=>true,
                'data'=>$lyceen->getPerson()->getNom()
            ))
            ->add('prenom',TextType::class,array(
                'label'=>'Prénom',
                'required'=>false,
                'disabled'=>true,
                'data'=>$lyceen->getPerson()->getPrenom()
            ))
            ->add('dateNaissance',DateType::class,array(
                'label'=>'Date de naissance',
                'format'=>'dd MMMM yyyy',
                'years'=>range(date('Y')-50,date('Y')),
                'placeholder'=>array('year'=>'Année','day'=>'Jour','month'=>'Mois'),
                'data'=>$lyceen->getPerson()->getDateNaissance(),
                'disabled'=>true

            ))
            ->add('lieuNaissance',TextType::class,array(
                'label'=>'Lieu de Naissance',
                'data'=>$lyceen->getPerson()->getLieuNaissance(),
                'disabled'=>true
            ));
        $personForm = $personFormBuilder->getForm();

        $infoEtudiantFormBuilder = $this->createFormBuilder($lyceen->getInfoEtudiant());
        $infoEtudiantFormBuilder
            ->add('adresse',TextType::class,array(
                'label'=>'Adresse',
                'data'=>$lyceen->getInfoEtudiant()->getAdresse()
            ))
            ->add('tel',TextType::class,array(
                'label'=>'Téléphone',
                'required'=>false,
                'data'=>$lyceen->getInfoEtudiant()->getTel()
            ))
            ->add('email',EmailType::class,array(
                'label'=>'E-mail',
                'required'=>false,
                'data'=>$lyceen->getInfoEtudiant()->getEmail()
            ))
            ->add('nomMere',TextType::class,array(
                'label'=>'Nom de votre mère',
                'disabled'=>true,
                'data'=>$lyceen->getInfoEtudiant()->getNomMere()
            ))
            ->add('nomPere',TextType::class,array(
                'label'=>'Nom de votre père',
                'disabled'=>true,
                'data'=>$lyceen->getInfoEtudiant()->getNomPere()
            ))
            ->add('telParent',TextType::class,array(
                'label'=>'Contact de votre parent',
                'required'=>false,
                'data'=>$lyceen->getInfoEtudiant()->getTelParent()
            ))
            ->add('emailParent',EmailType::class,array(
                'label'=>'E-mail de votre parent',
                'required'=>false,
                'data'=>$lyceen->getInfoEtudiant()->getEmailParent()
            ))
            ->add('nomTuteur',TextType::class,array(
                'label'=>'Nom de votre tuteur',
                'required'=>false,
                'data'=>$lyceen->getInfoEtudiant()->getNomTuteur()
            ));
        $infoEtudiantForm = $infoEtudiantFormBuilder->getForm();

        $lyceenFormBuilder = $this->createFormBuilder($lyceen);
        $lyceenFormBuilder
            ->add('dateReinscription',DateType::class,array(
                'label'=>'Date de réinscription',
                'format'=>'dd MMMM yyyy',
                'years'=>range(date('Y')-1,date('Y')+5),
                'placeholder'=>array('year'=>'Année','day'=>'Jour','month'=>'Mois'),
                'data'=>$lyceen->getDateReinscription()
            ))
            ->add('numeros',IntegerType::class,array(
                'label'=>'Numéros en classe',
                'required'=>true,
                'data'=>$lyceen->getNumeros(),
                'attr'=>array('min'=>1)
            ))
            ->add('anneeScolaire',TextType::class,array(
                'label'=>'Année scolaire',
                'required'=>true,
                'attr'=>array('placeholder'=>'2015-2016'),
                'data'=>$lyceen->getAnneeScolaire(),
                'disabled'=>true
            ))
            ->add('sonClasse',EntityType::class,array(
                'label'=>'Classe',
                'class'=>'Masca\EtudiantBundle\Entity\Classe',
                'required'=>true,
                'placeholder'=>'choississez ...',
                'empty_data'=>null,
                'choice_label'=>'intitule',
                'data'=>$lyceen->getSonClasse(),
                'disabled'=>true
            ));
        $lyceenForm = $lyceenFormBuilder->getForm();

        if($request->getMethod() == 'POST') {
            $personForm->handleRequest($request);
            $infoEtudiantForm->handleRequest($request);
            $lyceenForm->handleRequest($request);

            $this->getDoctrine()->getManager()->flush();
            return $this->redirect($this->generateUrl('details_lyceen',array('id'=>$lyceen->getId())));

        }

        return $this->render('MascaEtudiantBundle:Lycee:modifier.html.twig',array(
            'personForm'=>$personForm->createView(),
            'infoEtudiantForm'=>$infoEtudiantForm->createView(),
            'etudeForm'=>$lyceenForm->createView(),
            'lyceenId'=>$lyceen->getId()
        ));
    }

    /**
     * @param Request $request
     * @param Lyceen $lyceen
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/lycee/reinscription/{id}", name="reinscription_lyceen")
     */
    public function reinscriptionAction(Request $request, Lyceen $lyceen) {
        $lyceenFormBuilder = $this->createFormBuilder($lyceen);
        $lyceenFormBuilder
            ->add('dateReinscription',DateType::class,array(
                'label'=>'Date de réinscription',
                'format'=>'dd MMMM yyyy',
                'years'=>range(date('Y')-1,date('Y')+5),
                'placeholder'=>array('year'=>'Année','day'=>'Jour','month'=>'Mois'),
                'data'=>$lyceen->getDateReinscription()
            ))
            ->add('numeros',IntegerType::class,array(
                'label'=>'Numéros en classe',
                'required'=>true,
                'data'=>$lyceen->getNumeros(),
                'attr'=>array('min'=>1)
            ))
            ->add('anneeScolaire',TextType::class,array(
                'label'=>'Année scolaire',
                'required'=>true,
                'attr'=>array('placeholder'=>'2015-2016'),
                'data'=>$lyceen->getAnneeScolaire()
            ))
            ->add('sonClasse',EntityType::class,array(
                'label'=>'Classe',
                'class'=>'Masca\EtudiantBundle\Entity\Classe',
                'choice_label'=>'intitule',
                'required'=>true,
                'placeholder'=>'choississez ...',
                'empty_data'=>null,
                'data'=>$lyceen->getSonClasse()
            ));
        $lyceenForm = $lyceenFormBuilder->getForm();

        if($request->getMethod() == 'POST') {
            $lyceenForm->handleRequest($request);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirect($this->generateUrl('details_lyceen',array('id'=>$lyceen->getId())));
        }

        return $this->render('MascaEtudiantBundle:Lycee:reinscription.html.twig',array(
            'lyceenForm'=>$lyceenForm->createView(),
            'lyceen'=>$lyceen
        ));
    }
}