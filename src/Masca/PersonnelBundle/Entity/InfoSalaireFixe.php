<?php

namespace Masca\PersonnelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InfoSalaireFixe
 *
 * @ORM\Table(name="info_salaire_fixe")
 * @ORM\Entity(repositoryClass="Masca\PersonnelBundle\Repository\InfoSalaireFixeRepository")
 */
class InfoSalaireFixe
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
     * @var float
     *
     * @ORM\Column(name="salaire", type="float")
     */
    private $salaire;

    /**
     * @var string
     *
     * @ORM\Column(name="TitrePoste", type="string", length=255)
     */
    private $titrePoste;

    /**
     * @var $status Status
     * @ORM\OneToOne(targetEntity="Masca\PersonnelBundle\Entity\Status", cascade={"persist","remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;

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
     * Set salaire
     *
     * @param float $salaire
     *
     * @return InfoSalaireFixe
     */
    public function setSalaire($salaire)
    {
        $this->salaire = $salaire;

        return $this;
    }

    /**
     * Get salaire
     *
     * @return float
     */
    public function getSalaire()
    {
        return $this->salaire;
    }

    /**
     * Set titrePoste
     *
     * @param string $titrePoste
     *
     * @return InfoSalaireFixe
     */
    public function setTitrePoste($titrePoste)
    {
        $this->titrePoste = $titrePoste;

        return $this;
    }

    /**
     * Get titrePoste
     *
     * @return string
     */
    public function getTitrePoste()
    {
        return $this->titrePoste;
    }

    /**
     * Set status
     *
     * @param \Masca\PersonnelBundle\Entity\Status $status
     *
     * @return InfoSalaireFixe
     */
    public function setStatus(\Masca\PersonnelBundle\Entity\Status $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \Masca\PersonnelBundle\Entity\Status
     */
    public function getStatus()
    {
        return $this->status;
    }
}
