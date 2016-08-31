<?php

namespace Masca\PersonnelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AvanceSalaire
 *
 * @ORM\Table(name="avance_salaire")
 * @ORM\Entity(repositoryClass="Masca\PersonnelBundle\Repository\AvanceSalaireRepository")
 */
class AvanceSalaire
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
     * @ORM\Column(name="somme", type="decimal", precision=10, scale=0)
     */
    private $somme;

    /**
     * @var Employer
     *
     * @ORM\ManyToOne(targetEntity="Masca\PersonnelBundle\Entity\Employer", inversedBy="sesAvances")
     * @ORM\JoinColumn(nullable=false)
     */
    private $employer;

    /**
     * AvanceSalaire constructor
     */
    public function __construct()
    {
        $this->date = new \DateTime();
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
     * @return AvanceSalaire
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
     * @return AvanceSalaire
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
     * Set somme
     *
     * @param string $somme
     *
     * @return AvanceSalaire
     */
    public function setSomme($somme)
    {
        $this->somme = $somme;

        return $this;
    }

    /**
     * Get somme
     *
     * @return string
     */
    public function getSomme()
    {
        return $this->somme;
    }

    /**
     * Set employer
     *
     * @param \Masca\PersonnelBundle\Entity\Employer $employer
     *
     * @return AvanceSalaire
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