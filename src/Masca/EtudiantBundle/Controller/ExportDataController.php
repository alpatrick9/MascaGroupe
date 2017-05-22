<?php
/**
 * Created by PhpStorm.
 * User: patmi
 * Date: 22/05/2017
 * Time: 15:45
 */

namespace Masca\EtudiantBundle\Controller;


use Masca\EtudiantBundle\Entity\Lyceen;
use Masca\EtudiantBundle\Entity\UniversitaireSonFiliere;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class ExportDataController extends Controller
{

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\StreamedResponse
     * @throws \PHPExcel_Exception
     * @Route("/export-lyceen", name="export_lyceen")
     */
    public function exportLyceenAction(Request $request) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }

        /**
         * @var $lyceens Lyceen[]
         */
        $lyceens = $this->getDoctrine()->getRepository("MascaEtudiantBundle:Lyceen")->findAll();

        /**
         * @var $phpExcelObject \PHPExcel
         */
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $phpExcelObject->getProperties()->setCreator("Masca Group")
            ->setTitle("Listes des élèves du lycée")
            ->setSubject("Elève lycée")
            ->setDescription("Listes des élèves du lycée")
            ->setKeywords("office 2005 openxml php")
            ->setCategory("List lycée file");

        $sheet = $phpExcelObject->setActiveSheetIndex(0);


        $sheet->setCellValue('A1', 'N° matricule');
        $sheet->setCellValue('B1', 'Nom');
        $sheet->setCellValue('C1', 'Prénom');
        $sheet->setCellValue('D1', 'Date de naissance');
        $sheet->setCellValue('E1', 'Lieu de naissance');
        $sheet->setCellValue('F1', 'Adresse');
        $sheet->setCellValue('G1', 'Père');
        $sheet->setCellValue('H1', 'Mère');
        $sheet->setCellValue('I1', 'Classe');

        for($i = 0; $i < sizeof($lyceens); $i++) {
            $row = $i + 2;
            $sheet->setCellValue('A'.$row, $lyceens[$i]->getPerson()->getNumMatricule());
            $sheet->setCellValue('B'.$row, $lyceens[$i]->getPerson()->getNom());
            $sheet->setCellValue('C'.$row, $lyceens[$i]->getPerson()->getPrenom());
            $sheet->setCellValue('D'.$row, $lyceens[$i]->getPerson()->getDateNaissance());
            $sheet->setCellValue('E'.$row, $lyceens[$i]->getPerson()->getLieuNaissance());
            $sheet->setCellValue('F'.$row, $lyceens[$i]->getInfoEtudiant()->getAdresse());
            $sheet->setCellValue('G'.$row, $lyceens[$i]->getInfoEtudiant()->getNomPere());
            $sheet->setCellValue('H'.$row, $lyceens[$i]->getInfoEtudiant()->getNomMere());
            $sheet->setCellValue('I'.$row, $lyceens[$i]->getSonClasse()->getIntitule());
        }

        $phpExcelObject->getActiveSheet()->setTitle('Liste des élèves du lycée');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);

        // create the writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'liste lycee.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     * @throws \PHPExcel_Exception
     * @Route("/export-univ", name="export_univ")
     */
    public function exportUniversitaireAction(Request $request) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_DAF')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }

        /**
         * @var $universitaire UniversitaireSonFiliere[]
         */
        $universitaire = $this->getDoctrine()->getRepository("MascaEtudiantBundle:UniversitaireSonFiliere")->findAll();

        /**
         * @var $phpExcelObject \PHPExcel
         */
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $phpExcelObject->getProperties()->setCreator("Masca Group")
            ->setTitle("Listes des étudiants")
            ->setSubject("Etudiant de l'université")
            ->setDescription("Listes des étudiant de l'univerité")
            ->setKeywords("office 2005 openxml php")
            ->setCategory("List université file");

        $sheet = $phpExcelObject->setActiveSheetIndex(0);


        $sheet->setCellValue('A1', 'N° matricule');
        $sheet->setCellValue('B1', 'Nom');
        $sheet->setCellValue('C1', 'Prénom');
        $sheet->setCellValue('D1', 'Date de naissance');
        $sheet->setCellValue('E1', 'Lieu de naissance');
        $sheet->setCellValue('F1', 'Adresse');
        $sheet->setCellValue('G1', 'Père');
        $sheet->setCellValue('H1', 'Mère');
        $sheet->setCellValue('I1', 'Filière');
        $sheet->setCellValue('J1', 'Niveau d\'étude');

        for($i = 0; $i < sizeof($universitaire); $i++) {
            $row = $i + 2;
            $sheet->setCellValue('A'.$row, $universitaire[$i]->getUniversitaire()->getPerson()->getNumMatricule());
            $sheet->setCellValue('B'.$row, $universitaire[$i]->getUniversitaire()->getPerson()->getNom());
            $sheet->setCellValue('C'.$row, $universitaire[$i]->getUniversitaire()->getPerson()->getPrenom());
            $sheet->setCellValue('D'.$row, $universitaire[$i]->getUniversitaire()->getPerson()->getDateNaissance());
            $sheet->setCellValue('E'.$row, $universitaire[$i]->getUniversitaire()->getPerson()->getLieuNaissance());
            $sheet->setCellValue('F'.$row, $universitaire[$i]->getUniversitaire()->getInfoEtudiant()->getAdresse());
            $sheet->setCellValue('G'.$row, $universitaire[$i]->getUniversitaire()->getInfoEtudiant()->getNomPere());
            $sheet->setCellValue('H'.$row, $universitaire[$i]->getUniversitaire()->getInfoEtudiant()->getNomMere());
            $sheet->setCellValue('I'.$row, $universitaire[$i]->getSonFiliere()->getIntitule());
            $sheet->setCellValue('J'.$row, $universitaire[$i]->getSonNiveauEtude()->getIntitule());
        }

        $phpExcelObject->getActiveSheet()->setTitle('Liste des étudiants');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);

        // create the writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'liste universite.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }
}