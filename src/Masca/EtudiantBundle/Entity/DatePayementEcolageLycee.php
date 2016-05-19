<?php

namespace Masca\EtudiantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * DatePayementEcolageLycee
 *
 * @ORM\Table(name="date_payement_ecolage_lycee")
 * @ORM\Entity(repositoryClass="Masca\EtudiantBundle\Repository\DatePayementEcolageLyceeRepository")
 */
class DatePayementEcolageLycee
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
     * @ORM\Column(name="datePayement", type="date")
     */
    private $datePayement;

    /**
     * @var float
     *
     * @ORM\Column(name="montant", type="float")
     */
    private $montant;

    /**
     * @var $fraisScolariteLyceen FraisScolariteLyceen
     *
     * @ORM\ManyToOne(targetEntity="Masca\EtudiantBundle\Entity\FraisScolariteLyceen")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fraisScolariteLyceen;

    /**
     * DatePayementEcolageLycee constructor.
     */
    public function __construct()
    {
        $this->datePayement = new \DateTime();
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
     * Set datePayement
     *
     * @param \DateTime $datePayement
     *
     * @return DatePayementEcolageLycee
     */
    public function setDatePayement($datePayement)
    {
        $this->datePayement = $datePayement;

        return $this;
    }

    /**
     * Get datePayement
     *
     * @return \DateTime
     */
    public function getDatePayement()
    {
        return $this->datePayement;
    }

    /**
     * Set montant
     *
     * @param float $montant
     *
     * @return DatePayementEcolageLycee
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return float
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set fraisScolariteLyceen
     *
     * @param \Masca\EtudiantBundle\Entity\FraisScolariteLyceen $fraisScolariteLyceen
     *
     * @return DatePayementEcolageLycee
     */
    public function setFraisScolariteLyceen(\Masca\EtudiantBundle\Entity\FraisScolariteLyceen $fraisScolariteLyceen)
    {
        $this->fraisScolariteLyceen = $fraisScolariteLyceen;

        return $this;
    }

    /**
     * Get fraisScolariteLyceen
     *
     * @return \Masca\EtudiantBundle\Entity\FraisScolariteLyceen
     */
    public function getFraisScolariteLyceen()
    {
        return $this->fraisScolariteLyceen;
    }
}
