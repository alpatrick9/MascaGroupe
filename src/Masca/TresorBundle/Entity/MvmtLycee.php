<?php

namespace Masca\TresorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MvmtLycee
 *
 * @ORM\Table(name="mvmt_lycee")
 * @ORM\Entity(repositoryClass="Masca\TresorBundle\Repository\MvmtLyceeRepository")
 */
class MvmtLycee
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var float
     *
     * @ORM\Column(name="somme", type="float")
     */
    private $somme;

    /**
     * @var string
     *
     * @ORM\Column(name="typeOperation", type="string", length=255)
     */
    private $typeOperation;
    
    /**
     * @var float
     *
     * @ORM\Column(name="soldePrecedente", type="float")
     */
    private $soldePrecedent;

    /**
     * @var float
     *
     * @ORM\Column(name="soldeApres", type="float")
     */
    private $soldeApres;

    /**
     * MvmtLycee constructor.
     */
    public function __construct()
    {
        $this->date = new \DateTime();
        $this->somme = 0;
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
     * @return MvmtLycee
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
     * Set description
     *
     * @param string $description
     *
     * @return MvmtLycee
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set somme
     *
     * @param float $somme
     *
     * @return MvmtLycee
     */
    public function setSomme($somme)
    {
        $this->somme = $somme;

        return $this;
    }

    /**
     * Get somme
     *
     * @return float
     */
    public function getSomme()
    {
        return $this->somme;
    }

    /**
     * Set typeOperation
     *
     * @param string $typeOperation
     *
     * @return MvmtLycee
     */
    public function setTypeOperation($typeOperation)
    {
        $this->typeOperation = $typeOperation;

        return $this;
    }

    /**
     * Get typeOperation
     *
     * @return string
     */
    public function getTypeOperation()
    {
        return $this->typeOperation;
    }

    /**
     * Set soldePrecedent
     *
     * @param float $soldePrecedent
     *
     * @return MvmtLycee
     */
    public function setSoldePrecedent($soldePrecedent)
    {
        $this->soldePrecedent = $soldePrecedent;

        return $this;
    }

    /**
     * Get soldePrecedent
     *
     * @return float
     */
    public function getSoldePrecedent()
    {
        return $this->soldePrecedent;
    }

    /**
     * Set soldeApres
     *
     * @param float $soldeApres
     *
     * @return MvmtLycee
     */
    public function setSoldeApres($soldeApres)
    {
        $this->soldeApres = $soldeApres;

        return $this;
    }

    /**
     * Get soldeApres
     *
     * @return float
     */
    public function getSoldeApres()
    {
        return $this->soldeApres;
    }
}
