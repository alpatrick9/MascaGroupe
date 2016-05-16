<?php

namespace Masca\EtudiantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UeParFiliere
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Masca\EtudiantBundle\Entity\UeParFiliereRepository")
 */
class UeParFiliere
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
     * @var $ue Ue
     *
     * @ORM\ManyToOne(targetEntity="Masca\EtudiantBundle\Entity\Ue")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ue;

    /**
     * @var $filiere Filiere
     *
     * @ORM\ManyToOne(targetEntity="Masca\EtudiantBundle\Entity\Filiere")
     * @ORM\JoinColumn(nullable=false)
     */
    private $filiere;

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
     * Set ue
     *
     * @param \Masca\EtudiantBundle\Entity\Ue $ue
     * @return UeParFiliere
     */
    public function setUe(\Masca\EtudiantBundle\Entity\Ue $ue)
    {
        $this->ue = $ue;
    
        return $this;
    }

    /**
     * Get ue
     *
     * @return \Masca\EtudiantBundle\Entity\Ue 
     */
    public function getUe()
    {
        return $this->ue;
    }

    /**
     * Set filiere
     *
     * @param \Masca\EtudiantBundle\Entity\Filiere $filiere
     * @return UeParFiliere
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
}