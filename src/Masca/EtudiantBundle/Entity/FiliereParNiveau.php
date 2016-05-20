<?php

namespace Masca\EtudiantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FiliereParNiveau
 *
 * @ORM\Table(name="filiere_par_niveau")
 * @ORM\Entity(repositoryClass="Masca\EtudiantBundle\Repository\FiliereParNiveauRepository")
 */
class FiliereParNiveau
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Filiere
     *
     * @ORM\ManyToOne(targetEntity="Masca\EtudiantBundle\Entity\Filiere")
     * @ORM\JoinColumn(nullable=false)
     */
    private $filiere;

    /**
     * @var NiveauEtude
     *
     * @ORM\ManyToOne(targetEntity="Masca\EtudiantBundle\Entity\NiveauEtude")
     * @ORM\JoinColumn(nullable=false)
     */
    private $niveau;
    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set filiere
     *
     * @param \Masca\EtudiantBundle\Entity\Filiere $filiere
     *
     * @return FiliereParNiveau
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
     * Set niveau
     *
     * @param \Masca\EtudiantBundle\Entity\NiveauEtude $niveau
     *
     * @return FiliereParNiveau
     */
    public function setNiveau(\Masca\EtudiantBundle\Entity\NiveauEtude $niveau)
    {
        $this->niveau = $niveau;

        return $this;
    }

    /**
     * Get niveau
     *
     * @return \Masca\EtudiantBundle\Entity\NiveauEtude
     */
    public function getNiveau()
    {
        return $this->niveau;
    }
}
