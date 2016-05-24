<?php

namespace Masca\EtudiantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Classe
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Masca\EtudiantBundle\Entity\ClasseRepository")
 */
class Classe
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
     * @var $niveauEtude NiveauEtude
     *
     * @ORM\ManyToOne(targetEntity="Masca\EtudiantBundle\Entity\NiveauEtude")
     * @ORM\JoinColumn(nullable=false)
     */
    private $niveauEtude;

    /**
     * @var $emploiDuTemps EmploiDuTempsLycee[]
     * @ORM\OneToMany(targetEntity="Masca\EtudiantBundle\Entity\EmploiDuTempsLycee", mappedBy="classe", cascade={"remove"})
     */
    private $emploiDuTemps;

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
     * @return Classe
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
     * Set niveauEtude
     *
     * @param \Masca\EtudiantBundle\Entity\NiveauEtude $niveauEtude
     * @return Classe
     */
    public function setNiveauEtude(\Masca\EtudiantBundle\Entity\NiveauEtude $niveauEtude)
    {
        $this->niveauEtude = $niveauEtude;
    
        return $this;
    }

    /**
     * Get niveauEtude
     *
     * @return \Masca\EtudiantBundle\Entity\NiveauEtude 
     */
    public function getNiveauEtude()
    {
        return $this->niveauEtude;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->emploiDuTemps = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add emploiDuTemp
     *
     * @param \Masca\EtudiantBundle\Entity\EmploiDuTempsLycee $emploiDuTemp
     *
     * @return Classe
     */
    public function addEmploiDuTemp(\Masca\EtudiantBundle\Entity\EmploiDuTempsLycee $emploiDuTemp)
    {
        $this->emploiDuTemps[] = $emploiDuTemp;

        return $this;
    }

    /**
     * Remove emploiDuTemp
     *
     * @param \Masca\EtudiantBundle\Entity\EmploiDuTempsLycee $emploiDuTemp
     */
    public function removeEmploiDuTemp(\Masca\EtudiantBundle\Entity\EmploiDuTempsLycee $emploiDuTemp)
    {
        $this->emploiDuTemps->removeElement($emploiDuTemp);
    }

    /**
     * Get emploiDuTemps
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmploiDuTemps()
    {
        return $this->emploiDuTemps;
    }
}
