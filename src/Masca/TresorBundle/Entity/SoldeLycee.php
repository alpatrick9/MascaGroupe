<?php

namespace Masca\TresorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SoldeLycee
 *
 * @ORM\Table(name="solde_lycee")
 * @ORM\Entity(repositoryClass="Masca\TresorBundle\Repository\SoldeLyceeRepository")
 */
class SoldeLycee
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var float
     *
     * @ORM\Column(name="solde", type="float")
     */
    private $solde;

    /**
     * SoldeLycee constructor.
     */
    public function __construct()
    {
        $this->id = 1;
        $this->date = new \DateTime();
        $this->solde = 0;
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return SoldeLycee
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set solde
     *
     * @param float $solde
     *
     * @return SoldeLycee
     */
    public function setSolde($solde)
    {
        $this->solde = $solde;

        return $this;
    }

    /**
     * Get solde
     *
     * @return float
     */
    public function getSolde()
    {
        return $this->solde;
    }
}

