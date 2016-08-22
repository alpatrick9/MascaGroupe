<?php

namespace Masca\PersonnelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Masca\EtudiantBundle\Entity\Person;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Status
 *
 * @ORM\Table(name="status")
 * @ORM\Entity(repositoryClass="Masca\PersonnelBundle\Repository\StatusRepository")
 */
class Status
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
     * @var string
     *
     * @ORM\Column(name="type_salaire", type="string", length=255)
     */
    private $typeSalaire;

    /**
     * @var string
     *
     * @ORM\Column(name="type_poste", type="string", length=255)
     */
    private $typePoste;

    /**
     * @var $etablisement
     *
     * @ORM\Column(name="etablisement", type ="string", length=255)
     */
    private $etablisement;

    /**
     * @var $dateEmbauche \DateTime
     * @ORM\Column(name="dateEmbauche", type="date")
     */
    private $dateEmbauche;

    /**
     * @var $employer Employer
     * @ORM\ManyToOne(targetEntity="Masca\PersonnelBundle\Entity\Employer", inversedBy="sesStatus", cascade={"persist"})
     */
    private $employer;

    /**
     * @var $sesVolumeHoraires InfoVolumeHoraire[]
     *
     * @ORM\OneToMany(targetEntity="Masca\PersonnelBundle\Entity\InfoVolumeHoraire", mappedBy="status", cascade={"remove"})
     */
    private $sesVolumeHoraires;

    /**
     * @var $sesSalaireFixes InfoSalaireFixe[]
     * 
     * @ORM\OneToMany(targetEntity="Masca\PersonnelBundle\Entity\InfoSalaireFixe", mappedBy="status", cascade={"remove"})
     */
    private $sesSalaireFixes;


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
     * Set permanent
     *
     * @param string $typeSalaire
     *
     * @return Status
     */
    public function setTypeSalaire($typeSalaire)
    {
        $this->typeSalaire = $typeSalaire;

        return $this;
    }

    /**
     * Get permanent
     *
     * @return string
     */
    public function getTypeSalaire()
    {
        return $this->typeSalaire;
    }

    /**
     * Set libelePoste
     *
     * @param string $typePoste
     *
     * @return Status
     */
    public function setTypePoste($typePoste)
    {
        $this->typePoste = $typePoste;

        return $this;
    }

    /**
     * Get libelePoste
     *
     * @return string
     */
    public function getTypePoste()
    {
        return $this->typePoste;
    }

    /**
     * Set employer
     *
     * @param \Masca\PersonnelBundle\Entity\Employer $employer
     *
     * @return Status
     */
    public function setEmployer(\Masca\PersonnelBundle\Entity\Employer $employer = null)
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
     * Set dateEmbauche
     *
     * @param \DateTime $dateEmbauche
     *
     * @return Status
     */
    public function setDateEmbauche($dateEmbauche)
    {
        $this->dateEmbauche = $dateEmbauche;

        return $this;
    }

    /**
     * Get dateEmbauche
     *
     * @return \DateTime
     */
    public function getDateEmbauche()
    {
        return $this->dateEmbauche;
    }

    /**
     * Set etablisement
     *
     * @param string $etablisement
     *
     * @return Status
     */
    public function setEtablisement($etablisement)
    {
        $this->etablisement = $etablisement;

        return $this;
    }

    /**
     * Get etablisement
     *
     * @return string
     */
    public function getEtablisement()
    {
        return $this->etablisement;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sesVolumeHoraires = new \Doctrine\Common\Collections\ArrayCollection();
        $this->sesSalaireFixes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add sesVolumeHoraire
     *
     * @param \Masca\PersonnelBundle\Entity\InfoVolumeHoraire $sesVolumeHoraire
     *
     * @return Status
     */
    public function addSesVolumeHoraire(\Masca\PersonnelBundle\Entity\InfoVolumeHoraire $sesVolumeHoraire)
    {
        $this->sesVolumeHoraires[] = $sesVolumeHoraire;

        return $this;
    }

    /**
     * Remove sesVolumeHoraire
     *
     * @param \Masca\PersonnelBundle\Entity\InfoVolumeHoraire $sesVolumeHoraire
     */
    public function removeSesVolumeHoraire(\Masca\PersonnelBundle\Entity\InfoVolumeHoraire $sesVolumeHoraire)
    {
        $this->sesVolumeHoraires->removeElement($sesVolumeHoraire);
    }

    /**
     * Get sesVolumeHoraires
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSesVolumeHoraires()
    {
        return $this->sesVolumeHoraires;
    }

    /**
     * Add sesSalaireFix
     *
     * @param \Masca\PersonnelBundle\Entity\InfoSalaireFixe $sesSalaireFix
     *
     * @return Status
     */
    public function addSesSalaireFix(\Masca\PersonnelBundle\Entity\InfoSalaireFixe $sesSalaireFix)
    {
        $this->sesSalaireFixes[] = $sesSalaireFix;

        return $this;
    }

    /**
     * Remove sesSalaireFix
     *
     * @param \Masca\PersonnelBundle\Entity\InfoSalaireFixe $sesSalaireFix
     */
    public function removeSesSalaireFix(\Masca\PersonnelBundle\Entity\InfoSalaireFixe $sesSalaireFix)
    {
        $this->sesSalaireFixes->removeElement($sesSalaireFix);
    }

    /**
     * Get sesSalaireFixes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSesSalaireFixes()
    {
        return $this->sesSalaireFixes;
    }
}
