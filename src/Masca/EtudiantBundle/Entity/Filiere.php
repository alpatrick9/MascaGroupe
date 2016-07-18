<?php

namespace Masca\EtudiantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Filiere
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Masca\EtudiantBundle\Entity\FiliereRepository")
 */
class Filiere
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="intitule", type="string", length=255, unique=true)
     */
    private $intitule;

    /**
     * @var $lesEtudiants UniversitaireSonFiliere[]
     * @ORM\OneToMany(targetEntity="Masca\EtudiantBundle\Entity\UniversitaireSonFiliere", mappedBy="sonFiliere", cascade={"remove"})
     */
    private $lesEtudiants;

    /**
     * @var $lesUes UeParFiliere[]
     * @ORM\OneToMany(targetEntity="Masca\EtudiantBundle\Entity\UeParFiliere", mappedBy="filiere", cascade={"remove"})
     */
    private $lesUes;

    /**
     * @var $lesInformations InfoMatiereUniversite[]
     * @ORM\OneToMany(targetEntity="Masca\EtudiantBundle\Entity\InfoMatiereUniversite", mappedBy="filiere", cascade={"remove"})
     */
    private $lesInformations;

    /**
     * @var $lesEcolages GrilleFraisScolariteUniversite[]
     * @ORM\OneToMany(targetEntity="Masca\EtudiantBundle\Entity\GrilleFraisScolariteUniversite", mappedBy="filiere", cascade={"remove"})
     */
    private $lesEcolages;

    /**
     * @var $lesNiveaux FiliereParNiveau[]
     * @ORM\OneToMany(targetEntity="Masca\EtudiantBundle\Entity\FiliereParNiveau", mappedBy="filiere", cascade={"remove"})
     */
    private $lesNiveaux;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set intitule
     *
     * @param string $intitule
     * @return Filiere
     */
    public function setIntitule($intitule)
    {
        $this->intitule = $intitule;
    
        return $this;
    }

    /**
     * Get intitule
     *
     * @return string 
     */
    public function getIntitule()
    {
        return $this->intitule;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->lesEtudiants = new \Doctrine\Common\Collections\ArrayCollection();
        $this->lesUes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->lesInformations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->lesEcolages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->lesNiveaux = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add lesEtudiant
     *
     * @param \Masca\EtudiantBundle\Entity\UniversitaireSonFiliere $lesEtudiant
     *
     * @return Filiere
     */
    public function addLesEtudiant(\Masca\EtudiantBundle\Entity\UniversitaireSonFiliere $lesEtudiant)
    {
        $this->lesEtudiants[] = $lesEtudiant;

        return $this;
    }

    /**
     * Remove lesEtudiant
     *
     * @param \Masca\EtudiantBundle\Entity\UniversitaireSonFiliere $lesEtudiant
     */
    public function removeLesEtudiant(\Masca\EtudiantBundle\Entity\UniversitaireSonFiliere $lesEtudiant)
    {
        $this->lesEtudiants->removeElement($lesEtudiant);
    }

    /**
     * Get lesEtudiants
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLesEtudiants()
    {
        return $this->lesEtudiants;
    }

    /**
     * Add lesUe
     *
     * @param \Masca\EtudiantBundle\Entity\UeParFiliere $lesUe
     *
     * @return Filiere
     */
    public function addLesUe(\Masca\EtudiantBundle\Entity\UeParFiliere $lesUe)
    {
        $this->lesUes[] = $lesUe;

        return $this;
    }

    /**
     * Remove lesUe
     *
     * @param \Masca\EtudiantBundle\Entity\UeParFiliere $lesUe
     */
    public function removeLesUe(\Masca\EtudiantBundle\Entity\UeParFiliere $lesUe)
    {
        $this->lesUes->removeElement($lesUe);
    }

    /**
     * Get lesUes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLesUes()
    {
        return $this->lesUes;
    }

    /**
     * Add lesInformation
     *
     * @param \Masca\EtudiantBundle\Entity\InfoMatiereUniversite $lesInformation
     *
     * @return Filiere
     */
    public function addLesInformation(\Masca\EtudiantBundle\Entity\InfoMatiereUniversite $lesInformation)
    {
        $this->lesInformations[] = $lesInformation;

        return $this;
    }

    /**
     * Remove lesInformation
     *
     * @param \Masca\EtudiantBundle\Entity\InfoMatiereUniversite $lesInformation
     */
    public function removeLesInformation(\Masca\EtudiantBundle\Entity\InfoMatiereUniversite $lesInformation)
    {
        $this->lesInformations->removeElement($lesInformation);
    }

    /**
     * Get lesInformations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLesInformations()
    {
        return $this->lesInformations;
    }

    /**
     * Add lesEcolage
     *
     * @param \Masca\EtudiantBundle\Entity\GrilleFraisScolariteUniversite $lesEcolage
     *
     * @return Filiere
     */
    public function addLesEcolage(\Masca\EtudiantBundle\Entity\GrilleFraisScolariteUniversite $lesEcolage)
    {
        $this->lesEcolages[] = $lesEcolage;

        return $this;
    }

    /**
     * Remove lesEcolage
     *
     * @param \Masca\EtudiantBundle\Entity\GrilleFraisScolariteUniversite $lesEcolage
     */
    public function removeLesEcolage(\Masca\EtudiantBundle\Entity\GrilleFraisScolariteUniversite $lesEcolage)
    {
        $this->lesEcolages->removeElement($lesEcolage);
    }

    /**
     * Get lesEcolages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLesEcolages()
    {
        return $this->lesEcolages;
    }

    /**
     * Add lesNiveaux
     *
     * @param \Masca\EtudiantBundle\Entity\FiliereParNiveau $lesNiveaux
     *
     * @return Filiere
     */
    public function addLesNiveaux(\Masca\EtudiantBundle\Entity\FiliereParNiveau $lesNiveaux)
    {
        $this->lesNiveaux[] = $lesNiveaux;

        return $this;
    }

    /**
     * Remove lesNiveaux
     *
     * @param \Masca\EtudiantBundle\Entity\FiliereParNiveau $lesNiveaux
     */
    public function removeLesNiveaux(\Masca\EtudiantBundle\Entity\FiliereParNiveau $lesNiveaux)
    {
        $this->lesNiveaux->removeElement($lesNiveaux);
    }

    /**
     * Get lesNiveaux
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLesNiveaux()
    {
        return $this->lesNiveaux;
    }
}
