<?php

namespace Masca\EtudiantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ue
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Masca\EtudiantBundle\Entity\UeRepository")
 */
class Ue
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
     * @var $lesFilieres UeParFiliere[]
     * @ORM\OneToMany(targetEntity="Masca\EtudiantBundle\Entity\UeParFiliere", mappedBy="ue", cascade={"remove"})
     */
    private $lesFilieres;

    /**
     * @var $lesInformations InfoMatiereUniversite[]
     * @ORM\OneToMany(targetEntity="Masca\EtudiantBundle\Entity\InfoMatiereUniversite", mappedBy="ue", cascade={"remove"})
     */
    private $lesInformations;

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
     * @return Ue
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
        $this->lesFilieres = new \Doctrine\Common\Collections\ArrayCollection();
        $this->lesInformations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add lesFiliere
     *
     * @param \Masca\EtudiantBundle\Entity\UeParFiliere $lesFiliere
     *
     * @return Ue
     */
    public function addLesFiliere(\Masca\EtudiantBundle\Entity\UeParFiliere $lesFiliere)
    {
        $this->lesFilieres[] = $lesFiliere;

        return $this;
    }

    /**
     * Remove lesFiliere
     *
     * @param \Masca\EtudiantBundle\Entity\UeParFiliere $lesFiliere
     */
    public function removeLesFiliere(\Masca\EtudiantBundle\Entity\UeParFiliere $lesFiliere)
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
     * Add lesInformation
     *
     * @param \Masca\EtudiantBundle\Entity\InfoMatiereUniversite $lesInformation
     *
     * @return Ue
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
}
