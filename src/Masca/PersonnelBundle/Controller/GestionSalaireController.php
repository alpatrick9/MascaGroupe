<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 8/23/16
 * Time: 3:54 PM
 */

namespace Masca\PersonnelBundle\Controller;


use Doctrine\DBAL\Exception\ConstraintViolationException;
use Masca\PersonnelBundle\Entity\AvanceSalaire;
use Masca\PersonnelBundle\Entity\Employer;
use Masca\PersonnelBundle\Entity\InfoSalaireFixe;
use Masca\PersonnelBundle\Entity\PointageEnseignant;
use Masca\PersonnelBundle\Entity\Salaire;
use Masca\PersonnelBundle\Type\AvanceSalaireType;
use Masca\PersonnelBundle\Type\SalaireType;
use Masca\TresorBundle\Entity\MvmtLycee;
use Masca\TresorBundle\Entity\MvmtUniversite;
use Masca\TresorBundle\Entity\SoldeLycee;
use Masca\TresorBundle\Entity\SoldeUniversite;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\Tests\Fixtures\TypeHinted;

/**
 * Class GestionSalaireController
 * @package Masca\PersonnelBundle\Controller
 * @Route("/salaire")
 */
class GestionSalaireController extends Controller
{

    /**
     * @param Request $request
     * @param Employer $employer
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/{id}", name="home_salaire")
     */
    public function indexAction(Request $request, Employer $employer)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')) {
            return $this->render("::message-layout.html.twig", [
                'message' => 'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink' => $request->headers->get('referer')
            ]);
        }

        /**
         * @var Employer[]
         */
        $avances = $this->getDoctrine()->getRepository('MascaPersonnelBundle:AvanceSalaire')->findBy(['employer' => $employer->getId()]);

        /**
         * @var Salaire[]
         */
        $salaires = $this->getDoctrine()->getRepository('MascaPersonnelBundle:Salaire')->findBy(['employer' => $employer->getId()]);

        return $this->render('MascaPersonnelBundle:Salaire:index.html.twig', [
            'employer' => $employer,
            'avances' => $avances,
            'salaires' => $salaires
        ]);
    }

    /**
     * @param Request $request
     * @param Employer $employer
     * @return \Symfony\Component\HttpFoundation\Response$
     * @Route("/avance/{id}", name="add_avance_salaire")
     */
    public function addAvanceSalaireAction(Request $request, Employer $employer)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')) {
            return $this->render("::message-layout.html.twig", [
                'message' => 'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink' => $request->headers->get('referer')
            ]);
        }

        $avance = new AvanceSalaire();
        $avance->setEmployer($employer);
        $form = $this->createForm(new AvanceSalaireType($this->getParameter('mois')), $avance);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $mvmt = null;
            $solde = null;
            $em = $this->getDoctrine()->getManager();
            try {
                switch ($avance->getCaisse()) {
                    case 'cu':
                        $mvmt = new MvmtUniversite();

                        /**
                         * @var $solde SoldeUniversite
                         */
                        $solde = $this->getDoctrine()->getRepository('MascaTresorBundle:SoldeUniversite')->find(1);
                        if (empty($solde)) {
                            $solde = new SoldeUniversite();
                            $em->persist($solde);
                        }
                        break;
                    case 'cl':
                        $mvmt = new MvmtLycee();
                        /**
                         * @var $solde SoldeLycee
                         */
                        $solde = $this->getDoctrine()->getRepository('MascaTresorBundle:SoldeLycee')->find(1);

                        if(empty($solde)) {
                            $solde = new SoldeLycee();
                            $em->persist($solde);
                        }

                        break;
                }

                $mvmt->setSomme($avance->getSomme());
                $mvmt->setDescription('Avance sur salaire de l\'employer matricule ' . $avance->getEmployer()->getPerson()->getNumMatricule() . ' mois de ' . $avance->getMois());

                $solde->setDate($mvmt->getDate());

                $mvmt->setSoldePrecedent($solde->getSolde());
                $mvmt->setTypeOperation('d');
                $solde->setSolde($solde->getSolde() - $mvmt->getSomme());
                $mvmt->setSoldeApres($solde->getSolde());
                $em->persist($mvmt);
                
                $em->persist($avance);
                $em->flush();
            } catch (ConstraintViolationException $e) {
                return $this->render('MascaPersonnelBundle:Salaire:formulaire-avance.html.twig', [
                    'form' => $form->createView(),
                    'info' => $avance,
                    'error_message' => 'Ces informations sont déjà enregistées!'
                ]);
            }
            return $this->redirect($this->generateUrl('home_salaire', ['id' => $employer->getId()]));
        }

        return $this->render('MascaPersonnelBundle:Salaire:formulaire-avance.html.twig', [
            'form' => $form->createView(),
            'info' => $avance
        ]);
    }

    /**
     * @param Request $request
     * @param AvanceSalaire $avanceSalaire
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/avance/delete/{id}", name="delete_avance_salaire")
     */
    public function deleteAvanceSalaireAction(Request $request, AvanceSalaire $avanceSalaire)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')) {
            return $this->render("::message-layout.html.twig", [
                'message' => 'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink' => $request->headers->get('referer')
            ]);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($avanceSalaire);
        $em->flush();
        return $this->redirect($this->generateUrl('home_salaire', ['id' => $avanceSalaire->getEmployer()->getId()]));
    }

    /**
     * @param Request $request
     * @param Employer $employer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/payement/{id}", name="payement_salaire")
     */
    public function addSalaireAction(Request $request, Employer $employer)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')) {
            return $this->render("::message-layout.html.twig", [
                'message' => 'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink' => $request->headers->get('referer')
            ]);
        }

        $salaire = new Salaire();
        $salaire->setEmployer($employer);

        $form = $this->createForm(new SalaireType($this->getParameter('mois')), $salaire);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            /**
             * @var $infoSalaireFixes InfoSalaireFixe[]
             */
            $infoSalaireFixes = $this->getDoctrine()->getRepository('MascaPersonnelBundle:InfoSalaireFixe')->findAllPostFixe($employer);

            $salaireFixes = [];

            foreach ($infoSalaireFixes as $fix) {
                switch ($fix->getStatus()->getEtablisement()) {
                    case 'lycee':
                        $salaire->setTotalSalaireL($salaire->getTotalSalaireL() + $fix->getSalaire());
                        break;
                    case 'universite':
                        $salaire->setTotalSalaireU($salaire->getTotalSalaireU() + $fix->getSalaire());
                        break;
                }
                array_push($salaireFixes, $fix->getSalaire());
            }
            $salaire->setDetailSalaireFixe($salaireFixes);

            /**
             * @var $avanceSalaires AvanceSalaire[]
             */
            $avanceSalaires = $this->getDoctrine()->getRepository('MascaPersonnelBundle:AvanceSalaire')->findBy(['employer' => $employer, 'mois' => $salaire->getMois(), 'annee' => $salaire->getAnnee()]);

            foreach ($avanceSalaires as $avanceSalaire) {
                switch ($avanceSalaire->getCaisse()) {
                    case 'cl':
                        $salaire->setTotalAvanceSalaireL($salaire->getTotalAvanceSalaireL() + $avanceSalaire->getSomme());
                        break;
                    case 'cu':
                        $salaire->setTotalAvanceSalaireU($salaire->getTotalAvanceSalaireU() + $avanceSalaire->getSomme());
                        break;
                }
            }

            /**
             * @var $pointages PointageEnseignant[]
             */
            $pointages = $this->getDoctrine()->getRepository('MascaPersonnelBundle:PointageEnseignant')->findPointageBy($salaire->getMois(), $salaire->getAnnee(), $employer);

            $salaireHoraires = [];

            foreach ($pointages as $pointage) {
                $salaire->setTotalHeures($salaire->getTotalHeures() + $pointage->getVolumeHoraire());
                if (empty($salaireHoraires[$pointage->getInfoTauxHoraire()->getTauxHoraire()])) {
                    $salaireHoraires[$pointage->getInfoTauxHoraire()->getTauxHoraire()] = $pointage->getVolumeHoraire();
                } else {
                    $salaireHoraires[$pointage->getInfoTauxHoraire()->getTauxHoraire()] += $pointage->getVolumeHoraire();
                }
                switch ($pointage->getEtablissement()) {
                    case 'lycee':
                        $salaire->setTotalSalaireL($salaire->getTotalSalaireL() + $pointage->getInfoTauxHoraire()->getTauxHoraire()*$pointage->getVolumeHoraire());
                        break;
                    case 'universite':
                        $salaire->setTotalSalaireU($salaire->getTotalSalaireU() + $pointage->getInfoTauxHoraire()->getTauxHoraire()*$pointage->getVolumeHoraire());
                        break;
                }
            }
            $salaire->setDetailSalaireHoraire($salaireHoraires);

            if ($this->getDoctrine()->getRepository('MascaPersonnelBundle:Salaire')->salaireNotValid($salaire->getEmployer(), $salaire->getMois(), $salaire->getAnnee())) {
                return $this->render('MascaPersonnelBundle:Salaire:formulaire-salaire.html.twig', [
                    'form' => $form->createView(),
                    'info' => $salaire,
                    'error_message' => 'Ces informations sont déjà enregistées!'
                ]);
            }

            $session = $this->get('session');
            $session->set('salaire', serialize($salaire));

            return $this->redirect($this->generateUrl('validation_salaire', ['id' => $employer->getId()]));
        }

        return $this->render('MascaPersonnelBundle:Salaire:formulaire-salaire.html.twig', [
            'form' => $form->createView(),
            'info' => $salaire
        ]);
    }

    /**
     * @param Request $request
     * @param Employer $employer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/validation/{id}", name="validation_salaire")
     */
    public function validationSalaireAction(Request $request, Employer $employer)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')) {
            return $this->render("::message-layout.html.twig", [
                'message' => 'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink' => $request->headers->get('referer')
            ]);
        }
        $session = $this->get('session');
        if (empty($session->get('salaire')))
            return $this->redirect($this->generateUrl('home_salaire', ['id' => $employer->getId()]));

        /**
         * @var $salaire Salaire
         */
        $salaire = unserialize($session->get('salaire'));

        $salaire->setEmployer($employer);

        $salaireBrute = 0;

        $totalSalaireFixe = 0;

        foreach ($salaire->getDetailSalaireFixe() as $somme) {
            $totalSalaireFixe += $somme;
        }

        $salaireBrute += $totalSalaireFixe;
        $salaireBrute += $salaire->getPrime();

        $totalHoraires = [];
        foreach ($salaire->getDetailSalaireHoraire() as $key => $value) {
            $totalHoraires[$key] = $key * $value;
            $salaireBrute += $totalHoraires[$key];
        }

        $retenuCnaps = ($salaireBrute * $salaire->getEmployer()->getTauxCnaps()) / 100;

        $salaireNet = $salaireBrute - $retenuCnaps - $salaire->getTotalAvanceSalaireL() - $salaire->getTotalAvanceSalaireU();

        $form = $this->createForm(new SalaireType($this->getParameter('mois')), $salaire);

        $anneeField = $form->get('annee');
        $options = $anneeField->getConfig()->getOptions();
        $options['disabled'] = true;
        $form->add('annee', ChoiceType::class, $options);

        $moisField = $form->get('mois');
        $options = $moisField->getConfig()->getOptions();
        $options['disabled'] = true;
        $form->add('mois', ChoiceType::class, $options);

        $primeField = $form->get('prime');
        $options = $primeField->getConfig()->getOptions();
        $options['disabled'] = true;
        $form->add('prime', NumberType::class, $options);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();

            try {
                $mvmtLycee = new MvmtLycee();
                $mvmtUniversite = new MvmtUniversite();
                if($salaire->getTotalSalaireL() >= $salaire->getTotalSalaireU()) {
                    $mvmtLycee->setSomme($salaire->getTotalSalaireL() + $salaire->getPrime() - $salaire->getTotalAvanceSalaireL() - $retenuCnaps);
                    $mvmtUniversite->setSomme($salaire->getTotalSalaireU() - $salaire->getTotalAvanceSalaireU());
                }
                else {
                    $mvmtLycee->setSomme($salaire->getTotalSalaireL() - $salaire->getTotalAvanceSalaireL());
                    $mvmtUniversite->setSomme($salaire->getTotalSalaireU() + $salaire->getPrime() - $salaire->getTotalAvanceSalaireU() - $retenuCnaps);
                }


                $mvmtLycee->setDescription('Salaire net de l\'employer matricule '.$salaire->getEmployer()->getPerson()->getNumMatricule().' mois de '.$salaire->getMois());
                /**
                 * @var $solde SoldeLycee
                 */
                $solde = $this->getDoctrine()->getRepository('MascaTresorBundle:SoldeLycee')->find(1);

                if(empty($solde)) {
                    $solde = new SoldeLycee();
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($solde);
                }

                $solde->setDate($mvmtLycee->getDate());

                $mvmtLycee->setSoldePrecedent($solde->getSolde());
                $mvmtLycee->setTypeOperation('d');
                $solde->setSolde($solde->getSolde() - $mvmtLycee->getSomme());
                $mvmtLycee->setSoldeApres($solde->getSolde());
                $em->persist($mvmtLycee);


                $mvmtUniversite->setDescription('Salaire net de l\'employer matricule '.$salaire->getEmployer()->getPerson()->getNumMatricule().' mois de '.$salaire->getMois());
                /**
                 * @var $solde SoldeUniversite
                 */
                $solde = $this->getDoctrine()->getRepository('MascaTresorBundle:SoldeUniversite')->find(1);
                if(empty($solde)) {
                    $solde = new SoldeUniversite();
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($solde);
                }

                $solde->setDate($mvmtUniversite->getDate());

                $mvmtUniversite->setSoldePrecedent($solde->getSolde());
                $mvmtUniversite->setTypeOperation('d');
                $solde->setSolde($solde->getSolde() + $mvmtUniversite->getSomme());
                $mvmtUniversite->setSoldeApres($solde->getSolde());
                $em->persist($mvmtUniversite);

                $em->persist($salaire);
                $em->flush();
            } catch (ConstraintViolationException $e) {
                return $this->render('MascaPersonnelBundle:Salaire:validation-salaire.html.twig', [
                    'salaire' => $salaire,
                    'salaireFixeBrute' => $totalSalaireFixe,
                    'salaireHoraireBrutes' => $totalHoraires,
                    'cnaps' => $retenuCnaps,
                    'totalBrute' => $salaireBrute,
                    'salaireNet' => $salaireNet,
                    'form' => $form->createView(),
                    'error_message' => 'une erreur d\'enregistrement s\'est produit!'
                ]);
            } finally {
                $session->remove('salaire');
            }
            return $this->redirect($this->generateUrl('home_salaire', ['id' => $employer->getId()]));
        }

        return $this->render('MascaPersonnelBundle:Salaire:validation-salaire.html.twig', [
            'salaire' => $salaire,
            'salaireFixeBrute' => $totalSalaireFixe,
            'salaireHoraireBrutes' => $totalHoraires,
            'cnaps' => $retenuCnaps,
            'totalBrute' => $salaireBrute,
            'salaireNet' => $salaireNet,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param Salaire $salaire
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/details/{id}", name="detail_salaire")
     */
    public function detailsSalaireAction(Request $request, Salaire $salaire)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')) {
            return $this->render("::message-layout.html.twig", [
                'message' => 'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink' => $request->headers->get('referer')
            ]);
        }
        $salaireBrute = 0;

        $totalSalaireFixe = 0;

        foreach ($salaire->getDetailSalaireFixe() as $somme) {
            $totalSalaireFixe += $somme;
        }

        $salaireBrute += $totalSalaireFixe;
        $salaireBrute += $salaire->getPrime();

        $totalHoraires = [];
        foreach ($salaire->getDetailSalaireHoraire() as $key => $value) {
            $totalHoraires[$key] = $key * $value;
            $salaireBrute += $totalHoraires[$key];
        }

        $retenuCnaps = ($salaireBrute * $salaire->getEmployer()->getTauxCnaps()) / 100;

        $salaireNet = $salaireBrute - $retenuCnaps - $salaire->getTotalAvanceSalaireL();

        return $this->render('MascaPersonnelBundle:Salaire:details-salaire.html.twig', [
            'salaire' => $salaire,
            'salaireFixeBrute' => $totalSalaireFixe,
            'salaireHoraireBrutes' => $totalHoraires,
            'cnaps' => $retenuCnaps,
            'totalBrute' => $salaireBrute,
            'salaireNet' => $salaireNet
        ]);
    }

    /**
     * @param Request $request
     * @param Salaire $salaire
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/delete/{id}", name = "delete_salaire")
     */
    public function deleteSalaireAction(Request $request, Salaire $salaire)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')) {
            return $this->render("::message-layout.html.twig", [
                'message' => 'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink' => $request->headers->get('referer')
            ]);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($salaire);
        $em->flush();
        return $this->redirect($this->generateUrl('home_salaire', ['id' => $salaire->getEmployer()->getId()]));
    }

    /**
     * @param Request $request
     * @param Salaire $salaire
     * @return Response|\Symfony\Component\HttpFoundation\Response
     * @Route("/print/{id}", name="print_fiche_paye")
     */
    public function printFichePayeAction(Request $request, Salaire $salaire)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')) {
            return $this->render("::message-layout.html.twig", [
                'message' => 'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink' => $request->headers->get('referer')
            ]);
        }

        $html = $this->forward('MascaPersonnelBundle:ImpressionSalaire:printFichePaye', [
            'salaire' => $salaire
        ])->getContent();

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => sprintf('inline; filename="%s"', 'out.pdf'),
            ]
        );
    }
}