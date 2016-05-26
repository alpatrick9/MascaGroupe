<?php

namespace Masca\EtudiantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AbsenceLyceen
 *
 * @ORM\Table(name="absence_lyceen",uniqueConstraints={@ORM\UniqueConstraint(name="idxUnique", columns={"dateAbsent", "partieJournee", "lyceen_id"})})
 * @ORM\Entity(repositoryClass="Masca\EtudiantBundle\Repository\AbsenceLyceenRepository")
 */
class AbsenceLyceen
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
     * @var Lyceen
     * 
     * @ORM\ManyToOne(targetEntity="Masca\EtudiantBundle\Entity\Lyceen")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lyceen;

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
     * @return AbsenceLyceen
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
     * @return AbsenceLyceen
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
     * Set lyceen
     *
     * @param \Masca\EtudiantBundle\Entity\Lyceen $lyceen
     *
     * @return AbsenceLyceen
     */
    public function setLyceen(\Masca\EtudiantBundle\Entity\Lyceen $lyceen)
    {
        $this->lyceen = $lyceen;

        return $this;
    }

    /**
     * Get lyceen
     *
     * @return \Masca\EtudiantBundle\Entity\Lyceen
     */
    public function getLyceen()
    {
        return $this->lyceen;
    }
}
