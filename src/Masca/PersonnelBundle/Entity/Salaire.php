<?php

namespace Masca\PersonnelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Salaire
 *
 * @ORM\Table(name="salaire",uniqueConstraints={@ORM\UniqueConstraint(name="idxUnique", columns={"mois", "annee", "employer_id"})}))
 * @ORM\Entity(repositoryClass="Masca\PersonnelBundle\Repository\SalaireRepository")
 */
class Salaire
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
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="mois", type="string", length=255)
     */
    private $mois;

    /**
     * @var string
     *
     * @ORM\Column(name="annee", type="string", length=4)
     */
    private $annee;

    /**
     * @var string
     *
     * @ORM\Column(name="totalAvance", type="decimal", precision=10, scale=0)
     */
    private $totalAvance;

    /**
     * @var string
     *
     * @ORM\Column(name="salaireFixe", type="decimal", precision=10, scale=0)
     */
    private $salaireFixe;

    /**
     * @var string
     *
     * @ORM\Column(name="tauxHoraire", type="decimal", precision=10, scale=0)
     */
    private $tauxHoraire;

    /**
     * @var int
     *
     * @ORM\Column(name="volumeHoraire", type="integer")
     */
    private $volumeHoraire;

    /**
     * @var string
     *
     * @ORM\Column(name="prime", type="decimal", precision=10, scale=0)
     */
    private $prime;

    /**
     * @var Employer
     *
     * @ORM\ManyToOne(targetEntity="Masca\PersonnelBundle\Entity\Employer", inversedBy="sesSalaires")
     * @ORM\JoinColumn(nullable=false)
     */
    private $employer;

    /**
     * Salaire constructor
     */
    public function __construct()
    {
        $this->date = new \DateTime();
        $this->annee = $this->date->format('Y');
        $this->totalAvance = 0;
        $this->prime = 0;
        $this->salaireFixe = 0;
        $this->tauxHoraire = 0;
        $this->volumeHoraire = 0;
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
     * @return Salaire
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
     * Set mois
     *
     * @param string $mois
     *
     * @return Salaire
     */
    public function setMois($mois)
    {
        $this->mois = $mois;

        return $this;
    }

    /**
     * Get mois
     *
     * @return string
     */
    public function getMois()
    {
        return $this->mois;
    }

    /**
     * Set totalAvance
     *
     * @param string $totalAvance
     *
     * @return Salaire
     */
    public function setTotalAvance($totalAvance)
    {
        $this->totalAvance = $totalAvance;

        return $this;
    }

    /**
     * Get totalAvance
     *
     * @return string
     */
    public function getTotalAvance()
    {
        return $this->totalAvance;
    }

    /**
     * Set salaireFixe
     *
     * @param string $salaireFixe
     *
     * @return Salaire
     */
    public function setSalaireFixe($salaireFixe)
    {
        $this->salaireFixe = $salaireFixe;

        return $this;
    }

    /**
     * Get salaireFixe
     *
     * @return string
     */
    public function getSalaireFixe()
    {
        return $this->salaireFixe;
    }

    /**
     * Set tauxHoraire
     *
     * @param string $tauxHoraire
     *
     * @return Salaire
     */
    public function setTauxHoraire($tauxHoraire)
    {
        $this->tauxHoraire = $tauxHoraire;

        return $this;
    }

    /**
     * Get tauxHoraire
     *
     * @return string
     */
    public function getTauxHoraire()
    {
        return $this->tauxHoraire;
    }

    /**
     * Set volumeHoraire
     *
     * @param integer $volumeHoraire
     *
     * @return Salaire
     */
    public function setVolumeHoraire($volumeHoraire)
    {
        $this->volumeHoraire = $volumeHoraire;

        return $this;
    }

    /**
     * Get volumeHoraire
     *
     * @return int
     */
    public function getVolumeHoraire()
    {
        return $this->volumeHoraire;
    }

    /**
     * Set prime
     *
     * @param string $prime
     *
     * @return Salaire
     */
    public function setPrime($prime)
    {
        $this->prime = $prime;

        return $this;
    }

    /**
     * Get prime
     *
     * @return string
     */
    public function getPrime()
    {
        return $this->prime;
    }

    /**
     * Set employer
     *
     * @param \Masca\PersonnelBundle\Entity\Employer $employer
     *
     * @return Salaire
     */
    public function setEmployer(\Masca\PersonnelBundle\Entity\Employer $employer)
    {
        $this->employer = $employer;

        return $this;
    }

    /**
     * Get employer
     *
     * @return \Masca\PersonnelBundle\Entity\Employer
     */
    public function getEmployer()
    {
        return $this->employer;
    }


    /**
     * Set annee
     *
     * @param string $annee
     *
     * @return Salaire
     */
    public function setAnnee($annee)
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * Get annee
     *
     * @return string
     */
    public function getAnnee()
    {
        return $this->annee;
    }
}
