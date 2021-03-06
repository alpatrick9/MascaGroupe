<?php

namespace Masca\PersonnelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Masca\EtudiantBundle\Entity\Person;

/**
 * Employer
 *
 * @ORM\Table(name="employer")
 * @ORM\Entity(repositoryClass="Masca\PersonnelBundle\Repository\EmployerRepository")
 */
class Employer
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
     * @var $tauxCnaps float
     *
     * @ORM\Column(name="tauxCnaps", type="float")
     */
    private $tauxCnaps;
    
    /**
     * @var $person Person
     * @ORM\OneToOne(targetEntity="Masca\EtudiantBundle\Entity\Person", cascade={"persist","remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $person;

    /**
     * @var $sesStatus Status[]
     *
     * @ORM\OneToMany(targetEntity="Masca\PersonnelBundle\Entity\Status", mappedBy="employer", cascade={"remove"})
     */
    private $sesStatus;

    /**
     * @var $sesAbsences AbsenceEmploye[]
     *
     * @ORM\OneToMany(targetEntity="Masca\PersonnelBundle\Entity\AbsenceEmploye", mappedBy="employer", cascade={"remove"})
     */
    private $sesAbsences;

    /**
     * @var $sesPointages PointageEnseignant[]
     *
     * @ORM\OneToMany(targetEntity="Masca\PersonnelBundle\Entity\PointageEnseignant", mappedBy="employer", cascade={"remove"})
     */
    private $sesPointages;

    /**
     * @var AvanceSalaire[]
     *
     * @ORM\OneToMany(targetEntity="Masca\PersonnelBundle\Entity\AvanceSalaire", mappedBy="employer", cascade={"remove"})
     */
    private $sesAvances;

    /**
     * @var Salaire[]
     *
     * @ORM\OneToMany(targetEntity="Masca\PersonnelBundle\Entity\Salaire", mappedBy="employer", cascade={"remove"})
     */
    private $sesSalaires;

    /**
     * Employer constructor.
     * @param float $tauxCnaps
     */
    public function __construct()
    {
        $this->tauxCnaps = 0;
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
     * Set person
     *
     * @param \Masca\EtudiantBundle\Entity\Person $person
     *
     * @return Employer
     */
    public function setPerson(\Masca\EtudiantBundle\Entity\Person $person = null)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person
     *
     * @return \Masca\EtudiantBundle\Entity\Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Set tauxCnaps
     *
     * @param float $tauxCnaps
     *
     * @return Employer
     */
    public function setTauxCnaps($tauxCnaps)
    {
        $this->tauxCnaps = $tauxCnaps;

        return $this;
    }

    /**
     * Get tauxCnaps
     *
     * @return float
     */
    public function getTauxCnaps()
    {
        return $this->tauxCnaps;
    }

    /**
     * Add sesStatus
     *
     * @param \Masca\PersonnelBundle\Entity\Status $sesStatus
     *
     * @return Employer
     */
    public function addSesStatus(\Masca\PersonnelBundle\Entity\Status $sesStatus)
    {
        $this->sesStatus[] = $sesStatus;

        return $this;
    }

    /**
     * Remove sesStatus
     *
     * @param \Masca\PersonnelBundle\Entity\Status $sesStatus
     */
    public function removeSesStatus(\Masca\PersonnelBundle\Entity\Status $sesStatus)
    {
        $this->sesStatus->removeElement($sesStatus);
    }

    /**
     * Get sesStatus
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSesStatus()
    {
        return $this->sesStatus;
    }
    

    /**
     * Add sesAbsence
     *
     * @param \Masca\PersonnelBundle\Entity\AbsenceEmploye $sesAbsence
     *
     * @return Employer
     */
    public function addSesAbsence(\Masca\PersonnelBundle\Entity\AbsenceEmploye $sesAbsence)
    {
        $this->sesAbsences[] = $sesAbsence;

        return $this;
    }

    /**
     * Remove sesAbsence
     *
     * @param \Masca\PersonnelBundle\Entity\AbsenceEmploye $sesAbsence
     */
    public function removeSesAbsence(\Masca\PersonnelBundle\Entity\AbsenceEmploye $sesAbsence)
    {
        $this->sesAbsences->removeElement($sesAbsence);
    }

    /**
     * Get sesAbsences
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSesAbsences()
    {
        return $this->sesAbsences;
    }

    /**
     * Add sesPointage
     *
     * @param \Masca\PersonnelBundle\Entity\PointageEnseignant $sesPointage
     *
     * @return Employer
     */
    public function addSesPointage(\Masca\PersonnelBundle\Entity\PointageEnseignant $sesPointage)
    {
        $this->sesPointages[] = $sesPointage;

        return $this;
    }

    /**
     * Remove sesPointage
     *
     * @param \Masca\PersonnelBundle\Entity\PointageEnseignant $sesPointage
     */
    public function removeSesPointage(\Masca\PersonnelBundle\Entity\PointageEnseignant $sesPointage)
    {
        $this->sesPointages->removeElement($sesPointage);
    }

    /**
     * Get sesPointages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSesPointages()
    {
        return $this->sesPointages;
    }

    /**
     * Add sesAvance
     *
     * @param \Masca\PersonnelBundle\Entity\AvanceSalaire $sesAvance
     *
     * @return Employer
     */
    public function addSesAvance(\Masca\PersonnelBundle\Entity\AvanceSalaire $sesAvance)
    {
        $this->sesAvances[] = $sesAvance;

        return $this;
    }

    /**
     * Remove sesAvance
     *
     * @param \Masca\PersonnelBundle\Entity\AvanceSalaire $sesAvance
     */
    public function removeSesAvance(\Masca\PersonnelBundle\Entity\AvanceSalaire $sesAvance)
    {
        $this->sesAvances->removeElement($sesAvance);
    }

    /**
     * Get sesAvances
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSesAvances()
    {
        return $this->sesAvances;
    }

    /**
     * Add sesSalaire
     *
     * @param \Masca\PersonnelBundle\Entity\Salaire $sesSalaire
     *
     * @return Employer
     */
    public function addSesSalaire(\Masca\PersonnelBundle\Entity\Salaire $sesSalaire)
    {
        $this->sesSalaires[] = $sesSalaire;

        return $this;
    }

    /**
     * Remove sesSalaire
     *
     * @param \Masca\PersonnelBundle\Entity\Salaire $sesSalaire
     */
    public function removeSesSalaire(\Masca\PersonnelBundle\Entity\Salaire $sesSalaire)
    {
        $this->sesSalaires->removeElement($sesSalaire);
    }

    /**
     * Get sesSalaires
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSesSalaires()
    {
        return $this->sesSalaires;
    }
}
