<?php

namespace Masca\EtudiantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GrilleFraisScolariteUniversite
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Masca\EtudiantBundle\Entity\GrilleFraisScolariteUniversiteRepository")
 */
class GrilleFraisScolariteUniversite
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
     * @var $filiere Filiere
     * @ORM\ManyToOne(targetEntity="Masca\EtudiantBundle\Entity\Filiere")
     * @ORM\JoinColumn(nullable=false)
     */
    private $filiere;

    /**
     * @var $niveauEtude NiveauEtude
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
     * Set montant
     *
     * @param float $montant
     * @return GrilleFraisScolariteUniversite
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
     * Set filiere
     *
     * @param \Masca\EtudiantBundle\Entity\Filiere $filiere
     * @return GrilleFraisScolariteUniversite
     */
    public function setFiliere(\Masca\EtudiantBundle\Entity\Filiere $filiere)
    {
        $this->filiere = $filiere;
    
        return $this;
    }

    /**
     * Get filiere
     *
     * @return \Masca\EtudiantBundle\Entity\Filiere 
     */
    public function getFiliere()
    {
        return $this->filiere;
    }

    /**
     * Set niveauEtude
     *
     * @param \Masca\EtudiantBundle\Entity\NiveauEtude $niveauEtude
     * @return GrilleFraisScolariteUniversite
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