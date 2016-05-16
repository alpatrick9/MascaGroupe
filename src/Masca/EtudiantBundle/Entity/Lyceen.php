<?php

namespace Masca\EtudiantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lyceen
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Masca\EtudiantBundle\Entity\LyceenRepository")
 */
class Lyceen
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
     * @var \DateTime
     *
     * @ORM\Column(name="dateReinscription", type="date", nullable=true)
     */
    private $dateReinscription;

    /**
     * @var integer
     * @ORM\Column(name="numeros", type="integer")
     */
    private $numeros;

    /**
     * @var string
     *
     * @ORM\Column(name="anneeScolaire", type="string", length=255)
     */
    private $anneeScolaire;

    /**
     * @var $person Person
     * @ORM\OneToOne(targetEntity="Masca\EtudiantBundle\Entity\Person", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $person;

    /**
     * @var $infoEtudiant InfoEtudiant
     * @ORM\OneToOne(targetEntity="Masca\EtudiantBundle\Entity\InfoEtudiant", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $infoEtudiant;

    /**
     * @var $sonClasse
     * @ORM\ManyToOne(targetEntity="Masca\EtudiantBundle\Entity\Classe")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sonClasse;

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
     * Set numeros
     *
     * @param integer $numeros
     * @return Lyceen
     */
    public function setNumeros($numeros)
    {
        $this->numeros = $numeros;
    
        return $this;
    }

    /**
     * Get numeros
     *
     * @return integer 
     */
    public function getNumeros()
    {
        return $this->numeros;
    }

    /**
     * Set anneeScolaire
     *
     * @param string $anneeScolaire
     * @return Lyceen
     */
    public function setAnneeScolaire($anneeScolaire)
    {
        $this->anneeScolaire = $anneeScolaire;
    
        return $this;
    }

    /**
     * Get anneeScolaire
     *
     * @return string 
     */
    public function getAnneeScolaire()
    {
        return $this->anneeScolaire;
    }

    /**
     * Set person
     *
     * @param \Masca\EtudiantBundle\Entity\Person $person
     * @return Lyceen
     */
    public function setPerson(\Masca\EtudiantBundle\Entity\Person $person)
    {
        $this->person = $person;
    
        return $this;
    }

    /**
     * Get person
     *
     * @return \Masca\EtudiantBundle\Entity\Person 
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Set infoEtudiant
     *
     * @param \Masca\EtudiantBundle\Entity\InfoEtudiant $infoEtudiant
     * @return Lyceen
     */
    public function setInfoEtudiant(\Masca\EtudiantBundle\Entity\InfoEtudiant $infoEtudiant)
    {
        $this->infoEtudiant = $infoEtudiant;
    
        return $this;
    }

    /**
     * Get infoEtudiant
     *
     * @return \Masca\EtudiantBundle\Entity\InfoEtudiant 
     */
    public function getInfoEtudiant()
    {
        return $this->infoEtudiant;
    }

    /**
     * Set sonClasse
     *
     * @param \Masca\EtudiantBundle\Entity\Classe $sonClasse
     * @return Lyceen
     */
    public function setSonClasse(\Masca\EtudiantBundle\Entity\Classe $sonClasse)
    {
        $this->sonClasse = $sonClasse;
    
        return $this;
    }

    /**
     * Get sonClasse
     *
     * @return \Masca\EtudiantBundle\Entity\Classe 
     */
    public function getSonClasse()
    {
        return $this->sonClasse;
    }

    /**
     * Set dateReinscription
     *
     * @param \DateTime $dateReinscription
     * @return Lyceen
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