<?php

namespace Masca\EtudiantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Semestre
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Masca\EtudiantBundle\Entity\SemestreRepository")
 */
class Semestre
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
     * @var $lesInformations InfoMatiereUniversite[]
     * @ORM\OneToMany(targetEntity="Masca\EtudiantBundle\Entity\InfoMatiereUniversite", mappedBy="semestre", cascade={"remove"})
     */
    private $lesInformations;

    /**
     * @var $lesFilieres UniversitaireSonFiliere[]
     * @ORM\OneToMany(targetEntity="Masca\EtudiantBundle\Entity\UniversitaireSonFiliere", mappedBy="semestre", cascade={"remove"})
     */
    private $lesFilieres;

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
     * @return Semestre
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
        $this->lesInformations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->lesFilieres = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add lesInformation
     *
     * @param \Masca\EtudiantBundle\Entity\InfoMatiereUniversite $lesInformation
     *
     * @return Semestre
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
     * Add lesFiliere
     *
     * @param \Masca\EtudiantBundle\Entity\UniversitaireSonFiliere $lesFiliere
     *
     * @return Semestre
     */
    public function addLesFiliere(\Masca\EtudiantBundle\Entity\UniversitaireSonFiliere $lesFiliere)
    {
        $this->lesFilieres[] = $lesFiliere;

        return $this;
    }

    /**
     * Remove lesFiliere
     *
     * @param \Masca\EtudiantBundle\Entity\UniversitaireSonFiliere $lesFiliere
     */
    public function removeLesFiliere(\Masca\EtudiantBundle\Entity\UniversitaireSonFiliere $lesFiliere)
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
}
