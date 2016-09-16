<?php

namespace Masca\TresorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SoldeUniversite
 *
 * @ORM\Table(name="solde_universite")
 * @ORM\Entity(repositoryClass="Masca\TresorBundle\Repository\SoldeUniversiteRepository")
 */
class SoldeUniversite
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
     * SoldeUniversite constructor.
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
     * @return SoldeUniversite
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
     * @return SoldeUniversite
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

