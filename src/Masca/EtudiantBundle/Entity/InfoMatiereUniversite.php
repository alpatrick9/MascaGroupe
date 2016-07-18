<?php

namespace Masca\EtudiantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InfoMatiereUniversite
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Masca\EtudiantBundle\Entity\InfoMatiereUniversiteRepository")
 */
class InfoMatiereUniversite
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
     * @var $matiere Matiere
     * @ORM\ManyToOne(targetEntity="Masca\EtudiantBundle\Entity\Matiere", inversedBy="lesInformations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $matiere;

    /**
     * @var $ue Ue
     * @ORM\ManyToOne(targetEntity="Masca\EtudiantBundle\Entity\Ue", inversedBy="lesInformations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ue;

    /**
     * @var $filiere Filiere
     * @ORM\ManyToOne(targetEntity="Masca\EtudiantBundle\Entity\Filiere", inversedBy="lesInformations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $filiere;

    /**
     * @var $semestre Semestre
     * @ORM\ManyToOne(targetEntity="Masca\EtudiantBundle\Entity\Semestre", inversedBy="lesInformations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $semestre;

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
     * Set matiere
     *
     * @param \Masca\EtudiantBundle\Entity\Matiere $matiere
     * @return InfoMatiereUniversite
     */
    public function setMatiere(\Masca\EtudiantBundle\Entity\Matiere $matiere)
    {
        $this->matiere = $matiere;
    
        return $this;
    }

    /**
     * Get matiere
     *
     * @return \Masca\EtudiantBundle\Entity\Matiere 
     */
    public function getMatiere()
    {
        return $this->matiere;
    }

    /**
     * Set ue
     *
     * @param \Masca\EtudiantBundle\Entity\Ue $ue
     * @return InfoMatiereUniversite
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
     * @return InfoMatiereUniversite
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
     * Set semestre
     *
     * @param \Masca\EtudiantBundle\Entity\Semestre $semestre
     * @return InfoMatiereUniversite
     */
    public function setSemestre(\Masca\EtudiantBundle\Entity\Semestre $semestre)
    {
        $this->semestre = $semestre;
    
        return $this;
    }

    /**
     * Get semestre
     *
     * @return \Masca\EtudiantBundle\Entity\Semestre 
     */
    public function getSemestre()
    {
        return $this->semestre;
    }
}