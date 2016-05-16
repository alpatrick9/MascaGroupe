<?php
/**
 * Created by PhpStorm.
 * User: patmi
 * Date: 21/04/2016
 * Time: 10:13
 */

namespace Masca\EtudiantBundle\Controller;


use Doctrine\ORM\EntityRepository;
use Masca\EtudiantBundle\Entity\FraisScolariteLyceen;
use Masca\EtudiantBundle\Entity\GrilleFraisScolariteLycee;
use Masca\EtudiantBundle\Entity\InfoEtudiant;
use Masca\EtudiantBundle\Entity\Lyceen;
use Masca\EtudiantBundle\Entity\LyceenNote;
use Masca\EtudiantBundle\Entity\LyceenNoteRepository;
use Masca\EtudiantBundle\Entity\LyceenRepository;
use Masca\EtudiantBundle\Entity\Person;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
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

    /**
     * @param Lyceen $lyceen
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/lycee/ecolage/accueil/{id}", name="ecolage_lyceen")
     */
    public function ecolageAction(Lyceen $lyceen) {
        /**
         * @var $motantEcolage GrilleFraisScolariteLycee
         */
        $motantEcolage = $this->getDoctrine()->getManager()
            ->getRepository('MascaEtudiantBundle:GrilleFraisScolariteLycee')->findOneByClasse($lyceen->getSonClasse());

        /**
         * @var $statusEcolages FraisScolariteLyceen[]
         */
        $statusEcolages = $this->getDoctrine()->getManager()
                        ->getRepository('MascaEtudiantBundle:FraisScolariteLyceen')
                            ->findBy(
                                array(
                                    'lyceen'=>$lyceen,
                                    'anneeScolaire'=>$lyceen->getAnneeScolaire()
                                ));

        return $this->render('MascaEtudiantBundle:Lycee:ecolage.html.twig', array(
            'lyceen'=>$lyceen,
            'statusEcolages'=> $statusEcolages,
            'montant'=>$motantEcolage->getMontant()
        ));
    }

    /**
     * @param Request $request
     * @param Lyceen $lyceen
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/lycee/ecolage/payer/{id}", name="payer_ecolage_lyceen")
     */
    public function payerEcolageAction(Request $request, Lyceen $lyceen) {
        $anneeData = explode('-',$lyceen->getAnneeScolaire());
        $choicesAnnee = array($anneeData[0]=>$anneeData[0],$anneeData[1]=>$anneeData[1]);

        /**
         * @var $motantEcolage GrilleFraisScolariteLycee
         */
        $motantEcolage = $this->getDoctrine()->getManager()
                                ->getRepository('MascaEtudiantBundle:GrilleFraisScolariteLycee')->findOneByClasse($lyceen->getSonClasse());

        $fraisScolariteLyceen = new FraisScolariteLyceen();
        $fraisScolariteLyceen->setAnneeScolaire($lyceen->getAnneeScolaire());
        $fraisScolariteLyceen->setLyceen($lyceen);

        $ecolageFromBuilder = $this->createFormBuilder($fraisScolariteLyceen);
        $ecolageFromBuilder
            ->add('mois',ChoiceType::class,array(
                'label'=>'Mois',
                'choices_as_values'=>true,
                'choices'=>array('Janvier'=>'Janvier','Fevrier'=>'Février','Mars'=>'Mars','Mai'=>'Mai','Juin'=>'Juin','Juillet'=>'Juillet','Aout'=>'Août','Septembre'=>'Septembre','Novermbre'=>'Novembre','Decembre'=>'Decembre'),
                'placeholder'=>'Choisissez...',
                'data'=>$fraisScolariteLyceen->getMois()
            ))
            ->add('annee',ChoiceType::class,array(
                'label'=>'Annee',
                'choices_as_values'=>true,
                'choices'=> $choicesAnnee,
                'placeholder'=>'Choisissez...',
                'data'=>$fraisScolariteLyceen->getAnnee()
            ))
            ->add('montant',IntegerType::class,array(
                'label'=>'Montant',
                'attr'=>array(
                        'placeholder'=>'la somme',
                        'min'=>0,
                        'max'=>$motantEcolage->getMontant()
                    )
            ));
        $ecolageFrom = $ecolageFromBuilder->getForm();

        if($request->getMethod() == 'POST') {
            $ecolageFrom->handleRequest($request);
            if($fraisScolariteLyceen->getMontant() == $motantEcolage->getMontant()) {
                $fraisScolariteLyceen->setStatus(true);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($fraisScolariteLyceen);
            $em->flush();
            return $this->redirect($this->generateUrl('ecolage_lyceen', array('id'=>$lyceen->getId())));
        }
        return $this->render('MascaEtudiantBundle:Lycee:payementecolage.html.twig', array(
            'ecolageForm'=>$ecolageFrom->createView(),
            'lyceen'=>$lyceen,
            'montant'=>$motantEcolage->getMontant()
        ));
    }

    /**
     * @param Request $request
     * @param FraisScolariteLyceen $fraisScolariteLyceen
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @ParamConverter("fraisScolariteLyceen", options={"mapping": {"frais_scolarite_id":"id"}})
     * @Route("/lycee/ecolage/regularisation-reste/{frais_scolarite_id}", name="regularisation_reste_ecolage_lyceen")
     */
    public function regularisationResteEcolageAction(Request $request, FraisScolariteLyceen $fraisScolariteLyceen) {
        $anneeData = explode('-',$fraisScolariteLyceen->getLyceen()->getAnneeScolaire());
        $choicesAnnee = array($anneeData[0]=>$anneeData[0],$anneeData[1]=>$anneeData[1]);

        /**
         * @var $motantEcolage GrilleFraisScolariteLycee
         */
        $motantEcolage = $this->getDoctrine()->getManager()
            ->getRepository('MascaEtudiantBundle:GrilleFraisScolariteLycee')->findOneByClasse($fraisScolariteLyceen->getLyceen()->getSonClasse());

        $ecolageFromBuilder = $this->createFormBuilder($fraisScolariteLyceen);
        $ecolageFromBuilder
            ->add('mois',ChoiceType::class,array(
                'label'=>'Mois',
                'choices_as_values'=>true,
                'choices'=>array('Janvier'=>'Janvier','Fevrier'=>'Février','Mars'=>'Mars','Mai'=>'Mai','Juin'=>'Juin','Juillet'=>'Juillet','Aout'=>'Août','Septembre'=>'Septembre','Novermbre'=>'Novembre','Decembre'=>'Decembre'),
                'placeholder'=>'Choisissez...',
                'data'=>$fraisScolariteLyceen->getMois(),
                'attr'=>['readonly'=>true]
            ))
            ->add('annee',ChoiceType::class,array(
                'label'=>'Annee',
                'choices_as_values'=>true,
                'choices'=> $choicesAnnee,
                'placeholder'=>'Choisissez...',
                'data'=>$fraisScolariteLyceen->getAnnee(),
                'attr'=>['readonly'=>true]
            ))
            ->add('montant',IntegerType::class,array(
                'label'=>'Complement de l\'ecolage',
                'attr'=>array(
                    'placeholder'=>'la somme',
                    'min'=>0,
                    'max'=>$motantEcolage->getMontant()-$fraisScolariteLyceen->getMontant()
                ),
                'data'=>null
            ));
        $ecolageFrom = $ecolageFromBuilder->getForm();

        if($request->getMethod() == 'POST') {
            $oldMontant = $fraisScolariteLyceen->getMontant();
            $ecolageFrom->handleRequest($request);
            $fraisScolariteLyceen->setMontant($oldMontant + $fraisScolariteLyceen->getMontant());
            if($fraisScolariteLyceen->getMontant() == $motantEcolage->getMontant())
                $fraisScolariteLyceen->setStatus(true);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirect($this->generateUrl('ecolage_lyceen',array('id'=>$fraisScolariteLyceen->getLyceen()->getId())));
        }
        return $this->render('MascaEtudiantBundle:Lycee:regularisation-reste-ecolage.html.twig', array(
            'form'=>$ecolageFrom->createView(),
            'renseignement'=>$fraisScolariteLyceen,
            'reste'=>$motantEcolage->getMontant()-$fraisScolariteLyceen->getMontant()
        ));
    }

    /**
     * @param Lyceen $lyceen
     * @return \Symfony\Component\HttpFoundation\Response
     * @ParamConverter("lyceen", options={"mapping": {"lyceen_id":"id"}})
     * @Route("/lycee/note/liste/{lyceen_id}", name="liste_notes_lyceen")
     */
    public function noteAction(Lyceen $lyceen) {
        $totalCoef = 0;

        $totalTrimestre1 = 0;
        $totalTrimestre2 = 0;
        $totalTrimestre3 = 0;

        $moyenneTrimestre1 = 0;
        $moyenneTrimestre2 = 0;
        $moyenneTrimestre3 = 0;

        /**
         * @var $notes LyceenNote[]
         */
        $notes = $this->getDoctrine()->getManager()->getRepository("MascaEtudiantBundle:LyceenNote")->findByLyceen($lyceen);
        foreach($notes as $note) {
            $totalCoef = $totalCoef + $note->getCoefficient();

            $totalTrimestre1 = $totalTrimestre1 + $note->getCoefficient()*$note->getNoteTrimestre1();
            $totalTrimestre2 = $totalTrimestre2 + $note->getCoefficient()*$note->getNoteTrimestre2();
            $totalTrimestre3 = $totalTrimestre3 + $note->getCoefficient()*$note->getNoteTrimestre3();

        }

        if($totalCoef != 0) {
            $moyenneTrimestre1 = $totalTrimestre1 / $totalCoef;
            $moyenneTrimestre2 = $totalTrimestre2 / $totalCoef;
            $moyenneTrimestre3 = $totalTrimestre3 / $totalCoef;
        }

        $moyenGeneral = ($moyenneTrimestre1 + $moyenneTrimestre2 + $moyenneTrimestre3)/3;
        return $this->render('MascaEtudiantBundle:Lycee:notes.html.twig',[
            'notes'=>$notes,
            'lyceen'=>$lyceen,
            'totalCoef'=>$totalCoef,
            'totalTrimestre1'=>$totalTrimestre1,
            'totalTrimestre2'=>$totalTrimestre2,
            'totalTrimestre3'=>$totalTrimestre3,
            'moyenne1'=>$moyenneTrimestre1,
            'moyenne2'=>$moyenneTrimestre2,
            'moyenne3'=>$moyenneTrimestre3,
            'moyenneGeneral'=>$moyenGeneral
        ]);
    }

    /**
     * @param Request $request
     * @param Lyceen $lyceen
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @ParamConverter("lyceen", options={"mapping":{"lyceen_id":"id"}})
     * @Route("/lycee/note/ajouter/{lyceen_id}", name="ajouter_note_lyceen")
     */
    public function ajouterNoteAction(Request $request, Lyceen $lyceen) {
        $note = new LyceenNote();
        $formBuilder = $this->createFormBuilder($note);
        $formBuilder
            ->add('matiere',EntityType::class,[
                'label'=>'Matière',
                'class'=>'Masca\EtudiantBundle\Entity\MatiereLycee',
                'choice_label'=>'intitule',
                'placeholder'=>'choisissez...'
            ])
            ->add('coefficient',IntegerType::class,[
                'label'=>'Coefficient',
                'attr'=>[
                    'min'=>1,
                    'max'=>10
                ],
            ])
            ->add('noteTrimestre1',NumberType::class,[
                'label'=>'Note 1èr trimestre (/20)'
            ])
            ->add('noteTrimestre2',NumberType::class,[
                'label'=>'Note 2nd trimestre (/20)',
                'required'=>false
            ])
            ->add('noteTrimestre3',NumberType::class,[
                'label'=>'Note 3ème trimestre(/20)',
                'required'=>false
            ]);
        $form = $formBuilder->getForm();
        if($request->getMethod() ==  'POST') {
            $form->handleRequest($request);
            $note->setLyceen($lyceen);
            if(!$this->getDoctrine()->getManager()
                    ->getRepository('MascaEtudiantBundle:LyceenNote')->isValid($lyceen, $note->getMatiere())) {
                $js = '<script  type="text/javascript">'.
                    'document.getElementById("DivInfo").style.display = "block";'.
                    '</script>';
                return $this->render('MascaEtudiantBundle:Lycee:ajoute-note.html.twig', [
                    'form'=>$form->createView(),
                    'lyceen'=>$lyceen,
                    'js'=>$js
                ]);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($note);
            $em->flush();
            return $this->redirect($this->generateUrl('liste_notes_lyceen',['lyceen_id'=>$lyceen->getId()]));
        }
        return $this->render('MascaEtudiantBundle:Lycee:ajoute-note.html.twig', [
            'form'=>$form->createView(),
            'lyceen'=>$lyceen
        ]);
    }
}