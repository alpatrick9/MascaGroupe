<?php

namespace Masca\EtudiantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InfoEtudiant
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Masca\EtudiantBundle\Entity\InfoEtudiantRepository")
 */
class InfoEtudiant
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
     * @ORM\Column(name="adresse", type="string", length=255)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=255, nullable=true)
     */
    private $tel;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="nomMere", type="string", length=255)
     */
    private $nomMere;

    /**
     * @var string
     *
     * @ORM\Column(name="nomPere", type="string", length=255)
     */
    private $nomPere;

    /**
     * @var string
     *
     * @ORM\Column(name="nomTuteur", type="string", length=255, nullable=true)
     */
    private $nomTuteur;

    /**
     * @var string
     *
     * @ORM\Column(name="telParent", type="string", length=255, nullable=true)
     */
    private $telParent;

    /**
     * @var string
     *
     * @ORM\Column(name="emailParent", type="string", length=255, nullable=true)
     */
    private $emailParent;

    /**
     * @var float
     * 
     * @ORM\Column(name="reduction", type="float", options={"default":0})
     */
    private $reduction;

    public function __construct()
    {
        $this->reduction = 0;
    }

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
     * Set adresse
     *
     * @param string $adresse
     * @return InfoEtudiant
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    
        return $this;
    }

    /**
     * Get adresse
     *
     * @return string 
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set tel
     *
     * @param string $tel
     * @return InfoEtudiant
     */
    public function setTel($tel)
    {
        $this->tel = $tel;
    
        return $this;
    }

    /**
     * Get tel
     *
     * @return string 
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return InfoEtudiant
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set nomMere
     *
     * @param string $nomMere
     * @return InfoEtudiant
     */
    public function setNomMere($nomMere)
    {
        $this->nomMere = $nomMere;
    
        return $this;
    }

    /**
     * Get nomMere
     *
     * @return string 
     */
    public function getNomMere()
    {
        return $this->nomMere;
    }

    /**
     * Set nomPere
     *
     * @param string $nomPere
     * @return InfoEtudiant
     */
    public function setNomPere($nomPere)
    {
        $this->nomPere = $nomPere;
    
        return $this;
    }

    /**
     * Get nomPere
     *
     * @return string 
     */
    public function getNomPere()
    {
        return $this->nomPere;
    }

    /**
     * Set nomTuteur
     *
     * @param string $nomTuteur
     * @return InfoEtudiant
     */
    public function setNomTuteur($nomTuteur)
    {
        $this->nomTuteur = $nomTuteur;
    
        return $this;
    }

    /**
     * Get nomTuteur
     *
     * @return string 
     */
    public function getNomTuteur()
    {
        return $this->nomTuteur;
    }

    /**
     * Set telParent
     *
     * @param string $telParent
     * @return InfoEtudiant
     */
    public function setTelParent($telParent)
    {
        $this->telParent = $telParent;
    
        return $this;
    }

    /**
     * Get telParent
     *
     * @return string 
     */
    public function getTelParent()
    {
        return $this->telParent;
    }

    /**
     * Set emailParent
     *
     * @param string $emailParent
     * @return InfoEtudiant
     */
    public function setEmailParent($emailParent)
    {
        $this->emailParent = $emailParent;
    
        return $this;
    }

    /**
     * Get emailParent
     *
     * @return string 
     */
    public function getEmailParent()
    {
        return $this->emailParent;
    }

    /**
     * Set reduction
     *
     * @param float $reduction
     *
     * @return InfoEtudiant
     */
    public function setReduction($reduction)
    {
        $this->reduction = $reduction;

        return $this;
    }

    /**
     * Get reduction
     *
     * @return float
     */
    public function getReduction()
    {
        return $this->reduction;
    }

}
