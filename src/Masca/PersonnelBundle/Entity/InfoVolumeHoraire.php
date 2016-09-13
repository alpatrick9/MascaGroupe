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
     * @ORM\OneToOne(targetEntity="Masca\PersonnelBundle\Entity\Status", inversedBy="sesVolumeHoraires", cascade={"persist","remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;

    /**
     * @var $sesMatieres MatiereUnivEnseigner[]
     * @ORM\OneToMany(targetEntity="Masca\PersonnelBundle\Entity\MatiereUnivEnseigner", mappedBy="info", cascade={"remove"})
     */
    private $sesMatieres;

    /**
     * @var $sesMatieresLycee MatiereLyceeEnseigner[]
     * @ORM\OneToMany(targetEntity="Masca\PersonnelBundle\Entity\MatiereLyceeEnseigner", mappedBy="info", cascade={"remove"})
     */
    private $sesMatieresLycee;

    /**
     * @var PointageEnseignant[]
     * 
     * @ORM\OneToMany(targetEntity="Masca\PersonnelBundle\Entity\PointageEnseignant", mappedBy="infoTauxHoraire", cascade={"remove"})
     */
    private $sesPointages;

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
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sesMatieres = new \Doctrine\Common\Collections\ArrayCollection();
        $this->sesMatieresLycee = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add sesMatiere
     *
     * @param \Masca\PersonnelBundle\Entity\MatiereUnivEnseigner $sesMatiere
     *
     * @return InfoVolumeHoraire
     */
    public function addSesMatiere(\Masca\PersonnelBundle\Entity\MatiereUnivEnseigner $sesMatiere)
    {
        $this->sesMatieres[] = $sesMatiere;

        return $this;
    }

    /**
     * Remove sesMatiere
     *
     * @param \Masca\PersonnelBundle\Entity\MatiereUnivEnseigner $sesMatiere
     */
    public function removeSesMatiere(\Masca\PersonnelBundle\Entity\MatiereUnivEnseigner $sesMatiere)
    {
        $this->sesMatieres->removeElement($sesMatiere);
    }

    /**
     * Get sesMatieres
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSesMatieres()
    {
        return $this->sesMatieres;
    }

    /**
     * Add sesMatieresLycee
     *
     * @param \Masca\PersonnelBundle\Entity\MatiereLyceeEnseigner $sesMatieresLycee
     *
     * @return InfoVolumeHoraire
     */
    public function addSesMatieresLycee(\Masca\PersonnelBundle\Entity\MatiereLyceeEnseigner $sesMatieresLycee)
    {
        $this->sesMatieresLycee[] = $sesMatieresLycee;

        return $this;
    }

    /**
     * Remove sesMatieresLycee
     *
     * @param \Masca\PersonnelBundle\Entity\MatiereLyceeEnseigner $sesMatieresLycee
     */
    public function removeSesMatieresLycee(\Masca\PersonnelBundle\Entity\MatiereLyceeEnseigner $sesMatieresLycee)
    {
        $this->sesMatieresLycee->removeElement($sesMatieresLycee);
    }

    /**
     * Get sesMatieresLycee
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSesMatieresLycee()
    {
        return $this->sesMatieresLycee;
    }

    /**
     * Add sesPointage
     *
     * @param \Masca\PersonnelBundle\Entity\PointageEnseignant $sesPointage
     *
     * @return InfoVolumeHoraire
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
}
