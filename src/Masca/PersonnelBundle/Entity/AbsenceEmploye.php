<?php

namespace Masca\PersonnelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AbsenceEmploye
 *
 * @ORM\Table(name="absence_employe",uniqueConstraints={@ORM\UniqueConstraint(name="idxUnique", columns={"dateAbsent", "partieJournee", "employer_id"})})
 * @ORM\Entity(repositoryClass="Masca\PersonnelBundle\Repository\AbsenceEmployeRepository")
 */
class AbsenceEmploye
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
     * @ORM\Column(name="dateAbsent", type="date")
     */
    private $dateAbsent;

    /**
     * @var string
     *
     * @ORM\Column(name="partieJournee", type="string", length=255)
     */
    private $partieJournee;

    /**
     * @var Employer
     * 
     * @ORM\ManyToOne(targetEntity="Masca\PersonnelBundle\Entity\Employer", inversedBy="sesAbsences")
     * @ORM\JoinColumn(nullable=false)
     */
    private $employer;


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
     * Set dateAbsent
     *
     * @param \DateTime $dateAbsent
     *
     * @return AbsenceEmploye
     */
    public function setDateAbsent($dateAbsent)
    {
        $this->dateAbsent = $dateAbsent;

        return $this;
    }

    /**
     * Get dateAbsent
     *
     * @return \DateTime
     */
    public function getDateAbsent()
    {
        return $this->dateAbsent;
    }

    /**
     * Set partieJournee
     *
     * @param string $partieJournee
     *
     * @return AbsenceEmploye
     */
    public function setPartieJournee($partieJournee)
    {
        $this->partieJournee = $partieJournee;

        return $this;
    }

    /**
     * Get partieJournee
     *
     * @return string
     */
    public function getPartieJournee()
    {
        return $this->partieJournee;
    }

    /**
     * Set employer
     *
     * @param \Masca\PersonnelBundle\Entity\Employer $employer
     *
     * @return AbsenceEmploye
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
}
