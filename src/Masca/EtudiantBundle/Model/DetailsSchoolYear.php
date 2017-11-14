<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/14/17
 * Time: 9:13 AM
 */

namespace Masca\EtudiantBundle\Model;


class DetailsSchoolYear
{
    private $startYear;
    private $startMonth;
    private $endMonth;

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


}