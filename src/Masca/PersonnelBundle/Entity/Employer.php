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
    
}
