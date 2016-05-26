<?php

namespace Masca\EtudiantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RetardUniv
 *
 * @ORM\Table(name="retard_univ",uniqueConstraints={@ORM\UniqueConstraint(name="idxUnique", columns={"dateRetard", "heure", "universitaire_id"})})
 * @ORM\Entity(repositoryClass="Masca\EtudiantBundle\Repository\RetardUnivRepository")
 */
class RetardUniv
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
     * @ORM\Column(name="dateRetard", type="date")
     */
    private $dateRetard;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="heure", type="time")
     */
    private $heure;

    /**
     * @var Universitaire
     *
     * @ORM\ManyToOne(targetEntity="Masca\EtudiantBundle\Entity\Universitaire")
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
     * Set dateRetard
     *
     * @param \DateTime $dateRetard
     *
     * @return RetardUniv
     */
    public function setDateRetard($dateRetard)
    {
        $this->dateRetard = $dateRetard;

        return $this;
    }

    /**
     * Get dateRetard
     *
     * @return \DateTime
     */
    public function getDateRetard()
    {
        return $this->dateRetard;
    }

    /**
     * Set heure
     *
     * @param \DateTime $heure
     *
     * @return RetardUniv
     */
    public function setHeure($heure)
    {
        $this->heure = $heure;

        return $this;
    }

    /**
     * Get heure
     *
     * @return \DateTime
     */
    public function getHeure()
    {
        return $this->heure;
    }

    /**
     * Set universitaire
     *
     * @param \Masca\EtudiantBundle\Entity\Universitaire $universitaire
     *
     * @return RetardUniv
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
