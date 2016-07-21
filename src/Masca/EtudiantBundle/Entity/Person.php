<?php

namespace Masca\EtudiantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Masca\PersonnelBundle\Entity\Status;

/**
 * Person
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Masca\EtudiantBundle\Entity\PersonRepository")
 */
class Person
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
     * @ORM\Column(name="numMatricule", type="string", length=255, unique=true)
     */
    private $numMatricule;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateNaissance", type="date")
     */
    private $dateNaissance;

    /**
     * @var string
     *
     * @ORM\Column(name="lieuNaissance", type="string", length=255)
     */
    private $lieuNaissance;

    /**
     * @var integer
     *
     * @ORM\Column(name="numCin", type="bigint", nullable=true)
     */
    private $numCin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDelivranceCin", type="date", nullable=true)
     */
    private $dateDelivranceCin;

    /**
     * @var string
     *
     * @ORM\Column(name="lieuDelivranceCin", type="string", length=255, nullable=true)
     */
    private $lieuDelivranceCin;

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
     * Set numMatricule
     *
     * @param string $numMatricule
     * @return Person
     */
    public function setNumMatricule($numMatricule)
    {
        $this->numMatricule = $numMatricule;
    
        return $this;
    }

    /**
     * Get numMatricule
     *
     * @return string 
     */
    public function getNumMatricule()
    {
        return $this->numMatricule;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Person
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    
        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     * @return Person
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    
        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     * @return Person
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;
    
        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime 
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set lieuNaissance
     *
     * @param string $lieuNaissance
     * @return Person
     */
    public function setLieuNaissance($lieuNaissance)
    {
        $this->lieuNaissance = $lieuNaissance;
    
        return $this;
    }

    /**
     * Get lieuNaissance
     *
     * @return string 
     */
    public function getLieuNaissance()
    {
        return $this->lieuNaissance;
    }

    /**
     * Set numCin
     *
     * @param integer $numCin
     * @return Person
     */
    public function setNumCin($numCin)
    {
        $this->numCin = $numCin;
    
        return $this;
    }

    /**
     * Get numCin
     *
     * @return integer 
     */
    public function getNumCin()
    {
        return $this->numCin;
    }

    /**
     * Set dateDelivranceCin
     *
     * @param \DateTime $dateDelivranceCin
     * @return Person
     */
    public function setDateDelivranceCin($dateDelivranceCin)
    {
        $this->dateDelivranceCin = $dateDelivranceCin;
    
        return $this;
    }

    /**
     * Get dateDelivranceCin
     *
     * @return \DateTime 
     */
    public function getDateDelivranceCin()
    {
        return $this->dateDelivranceCin;
    }

    /**
     * Set lieuDelivranceCin
     *
     * @param string $lieuDelivranceCin
     * @return Person
     */
    public function setLieuDelivranceCin($lieuDelivranceCin)
    {
        $this->lieuDelivranceCin = $lieuDelivranceCin;
    
        return $this;
    }

    /**
     * Get lieuDelivranceCin
     *
     * @return string 
     */
    public function getLieuDelivranceCin()
    {
        return $this->lieuDelivranceCin;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
    }
}
