<?php

namespace Masca\PersonnelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * PointageEnseignant
 *
 * @ORM\Table(name="pointage_enseignant",uniqueConstraints={@ORM\UniqueConstraint(name="idxUnique", columns={"date", "heureDebut", "employer_id"})})
 * @ORM\Entity(repositoryClass="Masca\PersonnelBundle\Repository\PointageEnseignantRepository")
 */
class PointageEnseignant
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
     * @ORM\Column(name="annee", type="string", length=4)
     */
    private $annee;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="heureDebut", type="time")
     */
    private $heureDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="heureFin", type="time")
     */
    private $heureFin;

    /**
     * @var int
     *
     * @ORM\Column(name="volumeHoraire", type="integer")
     */
    private $volumeHoraire;

    /**
     * @var string
     *
     * @ORM\Column(name="matiere", type="string", length=255)
     */
    private $matiere;

    /**
     * @var string
     *
     * @ORM\Column(name="Etablissement", type="string", length=255)
     */
    private $etablissement;

    /**
     * @var string
     *
     * @ORM\Column(name="autre", type="string", length=255, nullable=true)
     */
    private $autre;

    /**
     * @var InfoVolumeHoraire
     * 
     * @ORM\ManyToOne(targetEntity="Masca\PersonnelBundle\Entity\InfoVolumeHoraire", inversedBy="sesPointages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $infoTauxHoraire;

    /**
     * @var Employer
     *
     * @ORM\ManyToOne(targetEntity="Masca\PersonnelBundle\Entity\Employer", inversedBy="sesPointages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $employer;

    /**
     * PointageEnseignant constructor
     */
    public function __construct()
    {
        $this->date = new \DateTime();
        $this->annee = $this->date->format('Y');
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
     * Set heureDebut
     *
     * @param \DateTime $heureDebut
     *
     * @return PointageEnseignant
     */
    public function setHeureDebut($heureDebut)
    {
        $this->heureDebut = $heureDebut;

        return $this;
    }

    /**
     * Get heureDebut
     *
     * @return \DateTime
     */
    public function getHeureDebut()
    {
        return $this->heureDebut;
    }

    /**
     * Set heureFin
     *
     * @param \DateTime $heureFin
     *
     * @return PointageEnseignant
     */
    public function setHeureFin($heureFin)
    {
        $this->heureFin = $heureFin;

        return $this;
    }

    /**
     * Get heureFin
     *
     * @return \DateTime
     */
    public function getHeureFin()
    {
        return $this->heureFin;
    }

    /**
     * Set volumeHoraire
     *
     * @param integer $volumeHoraire
     *
     * @return PointageEnseignant
     */
    public function setVolumeHoraire($volumeHoraire)
    {
        $this->volumeHoraire = $volumeHoraire;

        return $this;
    }

    /**
     * Get volumeHoraire
     *
     * @return int
     */
    public function getVolumeHoraire()
    {
        return $this->volumeHoraire;
    }

    /**
     * Set matière
     *
     * @param string $matiere
     *
     * @return PointageEnseignant
     */
    public function setMatiere($matiere)
    {
        $this->matiere = $matiere;

        return $this;
    }

    /**
     * Get matière
     *
     * @return string
     */
    public function getMatiere()
    {
        return $this->matiere;
    }

    /**
     * Set etablissement
     *
     * @param string $etablissement
     *
     * @return PointageEnseignant
     */
    public function setEtablissement($etablissement)
    {
        $this->etablissement = $etablissement;

        return $this;
    }

    /**
     * Get etablissement
     *
     * @return string
     */
    public function getEtablissement()
    {
        return $this->etablissement;
    }

    /**
     * Set autre
     *
     * @param string $autre
     *
     * @return PointageEnseignant
     */
    public function setAutre($autre)
    {
        $this->autre = $autre;

        return $this;
    }

    /**
     * Get autre
     *
     * @return string
     */
    public function getAutre()
    {
        return $this->autre;
    }

    /**
     * Set employer
     *
     * @param \Masca\PersonnelBundle\Entity\Employer $employer
     *
     * @return PointageEnseignant
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

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return PointageEnseignant
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
     * @return PointageEnseignant
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
     * Set annee
     *
     * @param string $annee
     *
     * @return PointageEnseignant
     */
    public function setAnnee($annee)
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * Get annee
     *
     * @return string
     */
    public function getAnnee()
    {
        return $this->annee;
    }

    /**
     * Set infoTauxHoraire
     *
     * @param \Masca\PersonnelBundle\Entity\InfoVolumeHoraire $infoTauxHoraire
     *
     * @return PointageEnseignant
     */
    public function setInfoTauxHoraire(\Masca\PersonnelBundle\Entity\InfoVolumeHoraire $infoTauxHoraire)
    {
        $this->infoTauxHoraire = $infoTauxHoraire;

        return $this;
    }

    /**
     * Get infoTauxHoraire
     *
     * @return \Masca\PersonnelBundle\Entity\InfoVolumeHoraire
     */
    public function getInfoTauxHoraire()
    {
        return $this->infoTauxHoraire;
    }
}
