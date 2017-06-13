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
     * @var float
     *
     * @ORM\Column(name="totalAvanceSalaireL", type="decimal", precision=10, scale=0)
     */
    private $totalAvanceSalaireL;

    /**
     * @var float
     *
     * @ORM\Column(name="totalAvanceSalaireU", type="decimal", precision=10, scale=0)
     */
    private $totalAvanceSalaireU;

    /**
     * @var float
     *
     * @ORM\Column(name="totalSalaireL", type="decimal", precision=10, scale=0)
     */
    private $totalSalaireL;

    /**
     * @var float
     *
     * @ORM\Column(name="totalSalaireU", type="decimal", precision=10, scale=0)
     */
    private $totalSalaireU;
    /**
     * @var array
     *
     * @ORM\Column(name="detailSalaireFixe", type="json_array")
     */
    private $detailSalaireFixe;

    /**
     * @var array
     * @ORM\Column(name="detailSalaireHoraire", type="json_array")
     */
    private $detailSalaireHoraire;

    /**
     * @var int
     *
     * @ORM\Column(name="totalHeures", type="integer")
     */
    private $totalHeures;

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
        $this->totalAvanceSalaireL = 0;
        $this->totalAvanceSalaireU = 0;
        $this->totalSalaireL = 0;
        $this->totalSalaireU = 0;
        $this->prime = 0;
        $this->totalHeures = 0;
    }

    /**
     * Get id
     *
     * @return integer
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

    /**
     * Set totalAvance
     *
     * @param string $totalAvanceSalaireL
     *
     * @return Salaire
     */
    public function setTotalAvanceSalaireL($totalAvanceSalaireL)
    {
        $this->totalAvanceSalaireL = $totalAvanceSalaireL;

        return $this;
    }

    /**
     * Get totalAvance
     *
     * @return string
     */
    public function getTotalAvanceSalaireL()
    {
        return $this->totalAvanceSalaireL;
    }

    private function addSalaireFixe($salaireFixe) {
        array_push($this->detailSalaireFixe, $salaireFixe);
    }
    /**
     * Set detailSalaireFixe
     *
     * @param array $detailSalaireFixe
     *
     * @return Salaire
     */
    public function setDetailSalaireFixe($detailSalaireFixe)
    {
        $this->detailSalaireFixe = [];
        foreach ($detailSalaireFixe as $item) {
            $this->addSalaireFixe($item);
        }

        return $this;
    }

    /**
     * Get detailSalaireFixe
     *
     * @return array
     */
    public function getDetailSalaireFixe()
    {
        return $this->detailSalaireFixe;
    }

    private function addSalaireHoraire($key, $value) {
        $this->detailSalaireHoraire[$key] = $value;
    }
    /**
     * Set detailSalaireHoraire
     *
     * @param array $detailSalaireHoraire
     *
     * @return Salaire
     */
    public function setDetailSalaireHoraire($detailSalaireHoraire)
    {
        $this->detailSalaireHoraire = [];
        foreach ($detailSalaireHoraire as $key => $value) {
            $this->addSalaireHoraire($key, $value);
        }

        return $this;
    }

    /**
     * Get detailSalaireHoraire
     *
     * @return array
     */
    public function getDetailSalaireHoraire()
    {
        return $this->detailSalaireHoraire;
    }

    /**
     * Set totalHeures
     *
     * @param integer $totalHeures
     *
     * @return Salaire
     */
    public function setTotalHeures($totalHeures)
    {
        $this->totalHeures = $totalHeures;

        return $this;
    }

    /**
     * Get totalHeures
     *
     * @return integer
     */
    public function getTotalHeures()
    {
        return $this->totalHeures;
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
     * Set totalAvanceSalaireU
     *
     * @param string $totalAvanceSalaireU
     *
     * @return Salaire
     */
    public function setTotalAvanceSalaireU($totalAvanceSalaireU)
    {
        $this->totalAvanceSalaireU = $totalAvanceSalaireU;

        return $this;
    }

    /**
     * Get totalAvanceSalaireU
     *
     * @return string
     */
    public function getTotalAvanceSalaireU()
    {
        return $this->totalAvanceSalaireU;
    }

    /**
     * Set totalSalaireL
     *
     * @param string $totalSalaireL
     *
     * @return Salaire
     */
    public function setTotalSalaireL($totalSalaireL)
    {
        $this->totalSalaireL = $totalSalaireL;

        return $this;
    }

    /**
     * Get totalSalaireL
     *
     * @return string
     */
    public function getTotalSalaireL()
    {
        return $this->totalSalaireL;
    }

    /**
     * Set totalSalaireU
     *
     * @param string $totalSalaireU
     *
     * @return Salaire
     */
    public function setTotalSalaireU($totalSalaireU)
    {
        $this->totalSalaireU = $totalSalaireU;

        return $this;
    }

    /**
     * Get totalSalaireU
     *
     * @return string
     */
    public function getTotalSalaireU()
    {
        return $this->totalSalaireU;
    }
}
