<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 9/14/16
 * Time: 9:34 AM
 */

namespace Masca\PersonnelBundle\Controller;


use Masca\PersonnelBundle\Entity\Salaire;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ImpressionSalaireController
 * @package Masca\PersonnelBundle\Controller
 */
class ImpressionSalaireController extends Controller
{
    /**
     * @param Request $request
     * @param Salaire $salaire
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function printFichePayeAction(Request $request, Salaire $salaire) {
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
        foreach ($salaire->getDetailSalaireHoraire() as $key=>$value) {
            $totalHoraires[$key] = $key*$value;
            $salaireBrute += $totalHoraires[$key];
        }

        $retenuCnaps = ($salaireBrute * $salaire->getEmployer()->getTauxCnaps())/100;

        $salaireNet = $salaireBrute - $retenuCnaps -$salaire->getTotalAvanceSalaireL();

        $sommeForBilletage = $salaireNet;

        $billetage = [
            10000 => 0,
            5000 => 0,
            2000 => 0,
            1000 => 0,
            500 => 0,
            200 => 0,
            100 => 0,
            50 => 0,
            20 => 0,
            10 => 0
        ];

        foreach ($billetage as $key => $value) {
            while ($sommeForBilletage >= $key) {
                $sommeForBilletage = $sommeForBilletage - $key;
                $billetage[$key]++;
            }
        }

        return $this->render('MascaPersonnelBundle:Impression:print-fiche-paye.html.twig', [
            'salaire'=>$salaire,
            'salaireFixeBrute'=> $totalSalaireFixe,
            'salaireHoraireBrutes'=>$totalHoraires,
            'cnaps'=>$retenuCnaps,
            'totalBrute'=>$salaireBrute,
            'salaireNet'=>$salaireNet,
            'billetage' => $billetage
        ]);
    }

}