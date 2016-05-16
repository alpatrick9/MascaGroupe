<?php

namespace Masca\EtudiantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UniversitaireSonFiliere
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Masca\EtudiantBundle\Entity\UniversitaireSonFiliereRepository")
 */
class UniversitaireSonFiliere
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
     * @var integer
     *
     * @ORM\Column(name="anneeEtude", type="integer")
     */
    private $anneeEtude;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateReinscription", type="date", nullable=true)
     */
    private $dateReinscription;

    /**
     * @var $universitaire Universitaire
     *
     * @ORM\ManyToOne(targetEntity="Masca\EtudiantBundle\Entity\Universitaire")
     * @ORM\JoinColumn(nullable=false)
     */
    private $universitaire;

    /**
     * @var $sonFiliere Filiere
     *
     * @ORM\ManyToOne(targetEntity="Masca\EtudiantBundle\Entity\Filiere")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sonFiliere;

    /**
     * @var $sonNiveauEtude NiveauEtude
     *
     * @ORM\ManyToOne(targetEntity="Masca\EtudiantBundle\Entity\NiveauEtude")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sonNiveauEtude;

    /**
     * @var $semestre Semestre
     *
     * @ORM\ManyToOne(targetEntity="Masca\EtudiantBundle\Entity\Semestre")
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
     * Set anneeEtude
     *
     * @param integer $anneeEtude
     * @return UniversitaireSonFiliere
     */
    public function setAnneeEtude($anneeEtude)
    {
        $this->anneeEtude = $anneeEtude;
    
        return $this;
    }

    /**
     * Get anneeEtude
     *
     * @return integer 
     */
    public function getAnneeEtude()
    {
        return $this->anneeEtude;
    }

    /**
     * Set universitaire
     *
     * @param \Masca\EtudiantBundle\Entity\Universitaire $universitaire
     * @return UniversitaireSonFiliere
     */
    public function setUniversitaire(\Masca\EtudiantBundle\Entity\Universitaire $universitaire)
    {
        $this->universitaire = $universitaire;
    
        return $this;
    }

    /**
     * Get universitaire
     *
     * @return \Masca\EtudiantBundle\Entity\Universitaire 
     */
    public function getUniversitaire()
    {
        return $this->universitaire;
    }

    /**
     * Set sonFiliere
     *
     * @param \Masca\EtudiantBundle\Entity\Filiere $sonFiliere
     * @return UniversitaireSonFiliere
     */
    public function setSonFiliere(\Masca\EtudiantBundle\Entity\Filiere $sonFiliere)
    {
        $this->sonFiliere = $sonFiliere;
    
        return $this;
    }

    /**
     * Get sonFiliere
     *
     * @return \Masca\EtudiantBundle\Entity\Filiere 
     */
    public function getSonFiliere()
    {
        return $this->sonFiliere;
    }

    /**
     * Set sonNiveauEtude
     *
     * @param \Masca\EtudiantBundle\Entity\NiveauEtude $sonNiveauEtude
     * @return UniversitaireSonFiliere
     */
    public function setSonNiveauEtude(\Masca\EtudiantBundle\Entity\NiveauEtude $sonNiveauEtude)
    {
        $this->sonNiveauEtude = $sonNiveauEtude;
    
        return $this;
    }

    /**
     * Get sonNiveauEtude
     *
     * @return \Masca\EtudiantBundle\Entity\NiveauEtude 
     */
    public function getSonNiveauEtude()
    {
        return $this->sonNiveauEtude;
    }

    /**
     * Set semestre
     *
     * @param \Masca\EtudiantBundle\Entity\Semestre $semestre
     * @return UniversitaireSonFiliere
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

    /**
     * Set dateReinscription
     *
     * @param \DateTime $dateReinscription
     * @return UniversitaireSonFiliere
     */
    public function setDateReinscription($dateReinscription)
    {
        $this->dateReinscription = $dateReinscription;
    
        return $this;
    }

    /**
     * Get dateReinscription
     *
     * @return \DateTime 
     */
    public function getDateReinscription()
    {
        return $this->dateReinscription;
    }
}