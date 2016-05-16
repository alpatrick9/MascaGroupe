<?php
/**
 * Created by PhpStorm.
 * User: patmi
 * Date: 25/04/2016
 * Time: 19:51
 */

namespace Masca\EtudiantBundle\Controller;


use Masca\EtudiantBundle\Entity\InfoEtudiant;
use Masca\EtudiantBundle\Entity\Person;
use Masca\EtudiantBundle\Entity\Universitaire;
use Masca\EtudiantBundle\Entity\UniversitaireRepository;
use Masca\EtudiantBundle\Entity\UniversitaireSonFiliere;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class UniversiteController extends Controller
{
    /**
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/accueil/universite/{page}", name="accueil_universite", defaults={"page" = 1})
     */
    public function indexAction($page) {
        $nbParPage = 30;
        /**
         * @var $repository UniversitaireRepository
         */
        $repository = $this->getDoctrine()->getManager()
            ->getRepository('MascaEtudiantBundle:Universitaire');
        /**
         * @var $universitaires Universitaire[]
         */
        $universitaires = $repository->getUniversitiares($nbParPage,$page);

        return $this->render('MascaEtudiantBundle:Universite:index.html.twig',array(
            'universitaires'=>$universitaires,
            'page'=> $page,
            'nbPage' => ceil(count($universitaires)/$nbParPage)
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/universite/inscription/", name="inscription_universite")
     */
    public  function inscriptionAction(Request $request) {
        $person = new Person();
        $infoEtudiant = new InfoEtudiant();
        $universitaire = new Universitaire();
        $sonFiliere = new UniversitaireSonFiliere();

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
            ->add('lieuNaissance',TextType::class,array(
                'label'=>'Lieu de Naissance'
            ))
            ->add('numCin',IntegerType::class,array(
                'label'=>'Numeros CIN',
                'attr'=>array(
                    'min'=>100000000000,
                    'placeholder'=>112991012188),
                'required'=>false
            ))
            ->add('dateDelivranceCin',DateType::class,array(
                'label'=>'Fait le',
                'format'=>'dd MMMM yyyy',
                'years'=>range(date('Y')-50,date('Y')),
                'placeholder'=>array('year'=>'Année','day'=>'Jour','month'=>'Mois'),
                'required'=>false
            ))
            ->add('lieuDelivranceCin',TextType::class, array(
               'label'=>'à',
                'required'=>false
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

        $universitaireFormBuilder = $this->createFormBuilder($universitaire);
        $universitaireFormBuilder
            ->add('serieBacc',TextType::class,array(
                'label'=>'Série du bacc'
            ));
        $universitaireForm = $universitaireFormBuilder->getForm();

        $sonFiliereFormBuilder = $this->createFormBuilder($sonFiliere);
        $sonFiliereFormBuilder
            ->add('semestre',EntityType::class,array(
                'label'=>'Semestre',
                'class'=>'Masca\EtudiantBundle\Entity\Semestre',
                'choice_label'=>'intitule',
                'placeholder'=>'choisissez...'
            ))
            ->add('sonFiliere',EntityType::class,array(
                'label'=>'Filière',
                'class'=>'Masca\EtudiantBundle\Entity\Filiere',
                'choice_label'=>'intitule',
                'placeholder'=>'Choisissez...'
            ))
            ->add('sonNiveauEtude',EntityType::class,array(
                'label'=>'Niveau d\'etude',
                'class'=>'Masca\EtudiantBundle\Entity\NiveauEtude',
                'choice_label'=>'intitule',
                'placeholder'=>'Choisissez...'
            ))
            ->add('anneeEtude',IntegerType::class,array(
                'label'=>'Année d\'etude',
                'attr'=>array('min'=>1900)
            ))
            ->add('dateReinscription',DateType::class,array(
                'label'=>'Date de réinscription',
                'format'=>'dd MMMM yyyy',
                'years'=>range(date('Y')-1,date('Y')+5),
                'placeholder'=>array('year'=>'Année','day'=>'Jour','month'=>'Mois')
            ));
        $sonFiliereForm = $sonFiliereFormBuilder->getForm();

        if($request->getMethod() == 'POST') {
            $personForm->handleRequest($request);
            $infoEtudiantForm->handleRequest($request);
            $universitaireForm->handleRequest($request);
            $sonFiliereForm->handleRequest($request);

            $universitaire->setPerson($person);
            $universitaire->setInfoEtudiant($infoEtudiant);

            $em = $this->getDoctrine()->getManager();
            $em->persist($universitaire);

            $sonFiliere->setUniversitaire($universitaire);
            $em->persist($sonFiliere);

            $em->flush();

            return $this->redirect($this->generateUrl('accueil_universite'));

        }
        return $this->render('MascaEtudiantBundle:Universite:inscription.html.twig',array(
            'personForm'=>$personForm->createView(),
            'infoEtudiantForm'=>$infoEtudiantForm->createView(),
            'etudeForm'=>$sonFiliereForm->createView(),
            'universitaireForm'=>$universitaireForm->createView()
        ));
    }

    /**
     * @param Universitaire $universitaire
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/universite/details/{id}", name="details_universite")
     */
    public function detailsAction(Universitaire $universitaire) {
        /**
         * @var $sesFilieres UniversitaireSonFiliere[]
         */
        $sesFilieres = $this->getDoctrine()->getManager()
                            ->getRepository('MascaEtudiantBundle:UniversitaireSonFiliere')->findByUniversitaire($universitaire);
        return $this->render('MascaEtudiantBundle:Universite:details.html.twig', array(
            'universitaire'=>$universitaire,
            'sesFilieres'=>$sesFilieres
        ));
    }

    /**
     * @param Request $request
     * @param Universitaire $universitaire
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/universite/modifier/{id}", name="modifier_universite")
     */
    public function modifierAction(Request $request, Universitaire $universitaire) {
        $personFormBuilder = $this->createFormBuilder($universitaire->getPerson());
        $personFormBuilder
            ->add('numMatricule',TextType::class,array(
                'label'=>'Numeros matricule',
                'disabled'=>true,
                'data'=>$universitaire->getPerson()->getNumMatricule()
            ))
            ->add('nom',TextType::class,array(
                'label'=>'Nom',
                'disabled'=>true,
                'data'=>$universitaire->getPerson()->getNom()
            ))
            ->add('prenom',TextType::class,array(
                'label'=>'Prénom',
                'required'=>false,
                'disabled'=>true,
                'data'=>$universitaire->getPerson()->getPrenom()
            ))
            ->add('dateNaissance',DateType::class,array(
                'label'=>'Date de naissance',
                'format'=>'dd MMMM yyyy',
                'years'=>range(date('Y')-50,date('Y')),
                'placeholder'=>array('year'=>'Année','day'=>'Jour','month'=>'Mois'),
                'data'=>$universitaire->getPerson()->getDateNaissance(),
                'disabled'=>true

            ))
            ->add('lieuNaissance',TextType::class,array(
                'label'=>'Lieu de Naissance',
                'data'=>$universitaire->getPerson()->getLieuNaissance(),
                'disabled'=>true
            ));
        $personForm = $personFormBuilder->getForm();

        $infoEtudiantFormBuilder = $this->createFormBuilder($universitaire->getInfoEtudiant());
        $infoEtudiantFormBuilder
            ->add('adresse',TextType::class,array(
                'label'=>'Adresse',
                'data'=>$universitaire->getInfoEtudiant()->getAdresse()
            ))
            ->add('tel',TextType::class,array(
                'label'=>'Téléphone',
                'required'=>false,
                'data'=>$universitaire->getInfoEtudiant()->getTel()
            ))
            ->add('email',EmailType::class,array(
                'label'=>'E-mail',
                'required'=>false,
                'data'=>$universitaire->getInfoEtudiant()->getEmail()
            ))
            ->add('nomMere',TextType::class,array(
                'label'=>'Nom de votre mère',
                'disabled'=>true,
                'data'=>$universitaire->getInfoEtudiant()->getNomMere()
            ))
            ->add('nomPere',TextType::class,array(
                'label'=>'Nom de votre père',
                'disabled'=>true,
                'data'=>$universitaire->getInfoEtudiant()->getNomPere()
            ))
            ->add('telParent',TextType::class,array(
                'label'=>'Contact de votre parent',
                'required'=>false,
                'data'=>$universitaire->getInfoEtudiant()->getTelParent()
            ))
            ->add('emailParent',EmailType::class,array(
                'label'=>'E-mail de votre parent',
                'required'=>false,
                'data'=>$universitaire->getInfoEtudiant()->getEmailParent()
            ))
            ->add('nomTuteur',TextType::class,array(
                'label'=>'Nom de votre tuteur',
                'required'=>false,
                'data'=>$universitaire->getInfoEtudiant()->getNomTuteur()
            ));
        $infoEtudiantForm = $infoEtudiantFormBuilder->getForm();

        if($request->getMethod() == 'POST') {
            $personForm->handleRequest($request);
            $infoEtudiantForm->handleRequest($request);

            $this->getDoctrine()->getManager()->flush();
            return $this->redirect($this->generateUrl('details_universite',array('id'=>$universitaire->getId())));

        }

        return $this->render('MascaEtudiantBundle:Universite:modifier.html.twig',array(
            'personForm'=>$personForm->createView(),
            'infoEtudiantForm'=>$infoEtudiantForm->createView(),
            'universiteId'=>$universitaire->getId()
        ));
    }

    /**
     * @param Request $request
     * @param UniversitaireSonFiliere $sonFiliere
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/universite/reinscription/{id}", name="reinscription_universite")
     */
    public function reinscriptionAction(Request $request, UniversitaireSonFiliere $sonFiliere) {
        $sonFiliereFormBuilder = $this->createFormBuilder($sonFiliere);
        $sonFiliereFormBuilder
            ->add('semestre',EntityType::class,array(
                'label'=>'Semestre',
                'class'=>'Masca\EtudiantBundle\Entity\Semestre',
                'choice_label'=>'intitule',
                'placeholder'=>'choisissez...',
                'data'=>$sonFiliere->getSemestre()
            ))
            ->add('sonFiliere',EntityType::class,array(
                'label'=>'Filière',
                'class'=>'Masca\EtudiantBundle\Entity\Filiere',
                'choice_label'=>'intitule',
                'placeholder'=>'Choisissez...',
                'data'=>$sonFiliere->getSonFiliere(),
                'attr'=>['readonly'=>true]
            ))
            ->add('sonNiveauEtude',EntityType::class,array(
                'label'=>'Niveau d\'etude',
                'class'=>'Masca\EtudiantBundle\Entity\NiveauEtude',
                'choice_label'=>'intitule',
                'placeholder'=>'Choisissez...',
                'data'=>$sonFiliere->getSonNiveauEtude()
            ))
            ->add('anneeEtude',IntegerType::class,array(
                'label'=>'Année d\'etude',
                'attr'=>array('min'=>1900),
                'data'=>$sonFiliere->getAnneeEtude()
            ))
            ->add('dateReinscription',DateType::class,array(
                'label'=>'Date de réinscription',
                'format'=>'dd MMMM yyyy',
                'years'=>range(date('Y')-1,date('Y')+5),
                'placeholder'=>array('year'=>'Année','day'=>'Jour','month'=>'Mois'),
                'data'=>$sonFiliere->getDateReinscription()
            ));
        $sonFiliereForm = $sonFiliereFormBuilder->getForm();

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
        return $this->render('MascaEtudiantBundle:Universite:details-etude.html.twig',[
            'sonFiliere'=>$sonFiliere
        ]);
    }
}