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
}