<?php

namespace Masca\EtudiantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NiveauEtude
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Masca\EtudiantBundle\Entity\NiveauEtudeRepository")
 */
class NiveauEtude
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
     * @ORM\OneToMany(targetEntity="Masca\EtudiantBundle\Entity\UniversitaireSonFiliere", mappedBy="sonNiveauEtude", cascade={"remove"})
     */
    private $lesEtudiants;

    /**
     * @var $lesUes UeParFiliere[]
     * @ORM\OneToMany(targetEntity="Masca\EtudiantBundle\Entity\UeParFiliere", mappedBy="niveau", cascade={"remove"})
     */
    private $lesUes;

    /**
     * @var $lesFilieres FiliereParNiveau[]
     * @ORM\OneToMany(targetEntity="Masca\EtudiantBundle\Entity\FiliereParNiveau", mappedBy="niveau", cascade={"remove"})
     */
    private $lesFilieres;

    /**
     * @var $lesEcolages GrilleFraisScolariteUniversite[]
     * @ORM\OneToMany(targetEntity="Masca\EtudiantBundle\Entity\GrilleFraisScolariteUniversite", mappedBy="niveauEtude", cascade={"remove"})
     */
    private $lesEcolages;

    /**
     * @var $lesClasses Classe[]
     * @ORM\OneToMany(targetEntity="Masca\EtudiantBundle\Entity\Classe", mappedBy="niveauEtude", cascade={"remove"})
     */
    private $lesClasses;

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
     * @return NiveauEtude
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
        $this->lesFilieres = new \Doctrine\Common\Collections\ArrayCollection();
        $this->lesEcolages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->lesClasses = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add lesEtudiant
     *
     * @param \Masca\EtudiantBundle\Entity\UniversitaireSonFiliere $lesEtudiant
     *
     * @return NiveauEtude
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
     * @return NiveauEtude
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
     * Add lesFiliere
     *
     * @param \Masca\EtudiantBundle\Entity\FiliereParNiveau $lesFiliere
     *
     * @return NiveauEtude
     */
    public function addLesFiliere(\Masca\EtudiantBundle\Entity\FiliereParNiveau $lesFiliere)
    {
        $this->lesFilieres[] = $lesFiliere;

        return $this;
    }

    /**
     * Remove lesFiliere
     *
     * @param \Masca\EtudiantBundle\Entity\FiliereParNiveau $lesFiliere
     */
    public function removeLesFiliere(\Masca\EtudiantBundle\Entity\FiliereParNiveau $lesFiliere)
    {
        $this->lesFilieres->removeElement($lesFiliere);
    }

    /**
     * Get lesFilieres
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLesFilieres()
    {
        return $this->lesFilieres;
    }

    /**
     * Add lesEcolage
     *
     * @param \Masca\EtudiantBundle\Entity\GrilleFraisScolariteUniversite $lesEcolage
     *
     * @return NiveauEtude
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
     * Add lesClass
     *
     * @param \Masca\EtudiantBundle\Entity\Classe $lesClass
     *
     * @return NiveauEtude
     */
    public function addLesClass(\Masca\EtudiantBundle\Entity\Classe $lesClass)
    {
        $this->lesClasses[] = $lesClass;

        return $this;
    }

    /**
     * Remove lesClass
     *
     * @param \Masca\EtudiantBundle\Entity\Classe $lesClass
     */
    public function removeLesClass(\Masca\EtudiantBundle\Entity\Classe $lesClass)
    {
        $this->lesClasses->removeElement($lesClass);
    }

    /**
     * Get lesClasses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLesClasses()
    {
        return $this->lesClasses;
    }
}
