<?php

namespace Masca\EtudiantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AbsenceUniv
 *
 * @ORM\Table(name="absence_univ",uniqueConstraints={@ORM\UniqueConstraint(name="idxUnique", columns={"dateAbsent", "partieJournee", "universitaire_id"})})
 * @ORM\Entity(repositoryClass="Masca\EtudiantBundle\Repository\AbsenceUnivRepository")
 */
class AbsenceUniv
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
     * @var Universitaire
     *
     * @ORM\ManyToOne(targetEntity="Masca\EtudiantBundle\Entity\Universitaire", inversedBy="sesAbsences")
     * @ORM\JoinColumn(nullable=false)
     */
    private $universitaire;


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
     * @return AbsenceUniv
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
     * @return AbsenceUniv
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
     * Set universitaire
     *
     * @param \Masca\EtudiantBundle\Entity\Universitaire $universitaire
     *
     * @return AbsenceUniv
     */
    public function setUniversitaire(\Masca\EtudiantBundle\Entity\Universitaire $universitaire)
    {
        $this->universitaire = $universitaire;

        return $this;
    }

    /**
     * Get universitaire
     *
     * @return \Masca\EtudiantBundle\Entity\Universitaire
     */
    public function getUniversitaire()
    {
        return $this->universitaire;
    }
}
