<?php

namespace Masca\EtudiantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DatePayementEcolageUniv
 *
 * @ORM\Table(name="date_payement_ecolage_univ")
 * @ORM\Entity(repositoryClass="Masca\EtudiantBundle\Repository\DatePayementEcolageUnivRepository")
 */
class DatePayementEcolageUniv
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
     * @var $fraisScolariteUniv FraisScolariteUniv
     *
     * @ORM\ManyToOne(targetEntity="Masca\EtudiantBundle\Entity\FraisScolariteUniv", inversedBy="historiquePayements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fraisScolariteUniv;

    /**
     * DatePayementEcolageUniv constructor.
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
     * @return DatePayementEcolageUniv
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
     * @return DatePayementEcolageUniv
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
     * Set fraisScolariteUniv
     *
     * @param \Masca\EtudiantBundle\Entity\FraisScolariteUniv $fraisScolariteUniv
     *
     * @return DatePayementEcolageUniv
     */
    public function setFraisScolariteUniv(\Masca\EtudiantBundle\Entity\FraisScolariteUniv $fraisScolariteUniv)
    {
        $this->fraisScolariteUniv = $fraisScolariteUniv;

        return $this;
    }

    /**
     * Get fraisScolariteUniv
     *
     * @return \Masca\EtudiantBundle\Entity\FraisScolariteUniv
     */
    public function getFraisScolariteUniv()
    {
        return $this->fraisScolariteUniv;
    }
}
