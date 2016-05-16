<?php

namespace Masca\EtudiantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GrilleFraisScolariteLycee
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Masca\EtudiantBundle\Entity\GrilleFraisScolariteLyceeRepository")
 */
class GrilleFraisScolariteLycee
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
     * @var float
     *
     * @ORM\Column(name="montant", type="float")
     */
    private $montant;

    /**
     * @var $classe Classe
     * @ORM\OneToOne(targetEntity="Masca\EtudiantBundle\Entity\Classe")
     * @ORM\JoinColumn(nullable=false)
     */
    private $classe;


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
     * Set montant
     *
     * @param float $montant
     * @return GrilleFraisScolariteLycee
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;
    
        return $this;
    }

    /**
     * Get montant
     *
     * @return float 
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set classe
     *
     * @param \Masca\EtudiantBundle\Entity\Classe $classe
     * @return GrilleFraisScolariteLycee
     */
    public function setClasse(\Masca\EtudiantBundle\Entity\Classe $classe)
    {
        $this->classe = $classe;
    
        return $this;
    }

    /**
     * Get classe
     *
     * @return \Masca\EtudiantBundle\Entity\Classe 
     */
    public function getClasse()
    {
        return $this->classe;
    }
}