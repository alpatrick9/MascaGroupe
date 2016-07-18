<?php

namespace Masca\EtudiantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Matiere
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Masca\EtudiantBundle\Entity\MatiereRepository")
 */
class Matiere
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
     * @var $lesEmploiDuTemps EmploiDuTempsUniv[]
     * @ORM\OneToMany(targetEntity="Masca\EtudiantBundle\Entity\EmploiDuTempsUniv", mappedBy="matiere", cascade={"remove"})
     */
    private $lesEmploiDuTemps;

    /**
     * @var $lesInformations InfoMatiereUniversite[]
     * @ORM\OneToMany(targetEntity="Masca\EtudiantBundle\Entity\InfoMatiereUniversite", mappedBy="matiere", cascade={"remove"})
     */
    private $lesInformations;

    /**
     * @var $lesUeFiliere MatiereParUeFiliere[]
     * @ORM\OneToMany(targetEntity="Masca\EtudiantBundle\Entity\MatiereParUeFiliere", mappedBy="matiere", cascade={"remove"})
     */
    private $lesUeFilieres;


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
     * @return Matiere
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
        $this->lesEmploiDuTemps = new \Doctrine\Common\Collections\ArrayCollection();
        $this->lesInformations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->lesUeFilieres = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add lesEmploiDuTemp
     *
     * @param \Masca\EtudiantBundle\Entity\EmploiDuTempsUniv $lesEmploiDuTemp
     *
     * @return Matiere
     */
    public function addLesEmploiDuTemp(\Masca\EtudiantBundle\Entity\EmploiDuTempsUniv $lesEmploiDuTemp)
    {
        $this->lesEmploiDuTemps[] = $lesEmploiDuTemp;

        return $this;
    }

    /**
     * Remove lesEmploiDuTemp
     *
     * @param \Masca\EtudiantBundle\Entity\EmploiDuTempsUniv $lesEmploiDuTemp
     */
    public function removeLesEmploiDuTemp(\Masca\EtudiantBundle\Entity\EmploiDuTempsUniv $lesEmploiDuTemp)
    {
        $this->lesEmploiDuTemps->removeElement($lesEmploiDuTemp);
    }

    /**
     * Get lesEmploiDuTemps
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLesEmploiDuTemps()
    {
        return $this->lesEmploiDuTemps;
    }

    /**
     * Add lesInformation
     *
     * @param \Masca\EtudiantBundle\Entity\InfoMatiereUniversite $lesInformation
     *
     * @return Matiere
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
     * Add lesUeFiliere
     *
     * @param \Masca\EtudiantBundle\Entity\MatiereParUeFiliere $lesUeFiliere
     *
     * @return Matiere
     */
    public function addLesUeFiliere(\Masca\EtudiantBundle\Entity\MatiereParUeFiliere $lesUeFiliere)
    {
        $this->lesUeFilieres[] = $lesUeFiliere;

        return $this;
    }

    /**
     * Remove lesUeFiliere
     *
     * @param \Masca\EtudiantBundle\Entity\MatiereParUeFiliere $lesUeFiliere
     */
    public function removeLesUeFiliere(\Masca\EtudiantBundle\Entity\MatiereParUeFiliere $lesUeFiliere)
    {
        $this->lesUeFilieres->removeElement($lesUeFiliere);
    }

    /**
     * Get lesUeFilieres
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLesUeFilieres()
    {
        return $this->lesUeFilieres;
    }
}
