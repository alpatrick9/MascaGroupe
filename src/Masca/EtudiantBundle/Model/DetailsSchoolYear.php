<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/14/17
 * Time: 9:13 AM
 */

namespace Masca\EtudiantBundle\Model;


use Masca\EtudiantBundle\Entity\Classe;

class DetailsSchoolYear
{
    private $startYear;
    private $startMonth;
    private $endMonth;

    /**
     * @var $classe Classe
     */
    private $classe;

    /**
     * @return mixed
     */
    public function getStartYear()
    {
        return $this->startYear;
    }

    /**
     * @param mixed $startYear
     */
    public function setStartYear($startYear)
    {
        $this->startYear = $startYear;
    }

    /**
     * @return mixed
     */
    public function getStartMonth()
    {
        return $this->startMonth;
    }

    /**
     * @param mixed $startMonth
     */
    public function setStartMonth($startMonth)
    {
        $this->startMonth = $startMonth;
    }

    /**
     * @return mixed
     */
    public function getEndMonth()
    {
        return $this->endMonth;
    }

    /**
     * @param mixed $endMonth
     */
    public function setEndMonth($endMonth)
    {
        $this->endMonth = $endMonth;
    }

    /**
     * @return Classe
     */
    public function getClasse()
    {
        return $this->classe;
    }

    /**
     * @param Classe $classe
     */
    public function setClasse($classe)
    {
        $this->classe = $classe;
    }


}