<?php

namespace Masca\PersonnelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InfoVolumeHoraire
 *
 * @ORM\Table(name="info_volume_horaire")
 * @ORM\Entity(repositoryClass="Masca\PersonnelBundle\Repository\InfoVolumeHoraireRepository")
 */
class InfoVolumeHoraire
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
     * @ORM\Column(name="tauxHoraire", type="float")
     */
    private $tauxHoraire;

    /**
     * @var string
     *
     * @ORM\Column(name="titrePoste", type="string", length=255)
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
     * Set tauxHoraire
     *
     * @param float $tauxHoraire
     *
     * @return InfoVolumeHoraire
     */
    public function setTauxHoraire($tauxHoraire)
    {
        $this->tauxHoraire = $tauxHoraire;

        return $this;
    }

    /**
     * Get tauxHoraire
     *
     * @return float
     */
    public function getTauxHoraire()
    {
        return $this->tauxHoraire;
    }

    /**
     * Set titrePoste
     *
     * @param string $titrePoste
     *
     * @return InfoVolumeHoraire
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
     * @return InfoVolumeHoraire
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
