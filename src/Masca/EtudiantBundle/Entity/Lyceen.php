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
     * @var string
     *
     * @ORM\Column(name="nb", type="text", nullable=true)
     */
    private $nb;

    /**
     * @var $person Person
     * @ORM\OneToOne(targetEntity="Masca\EtudiantBundle\Entity\Person", cascade={"persist","remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $person;

    /**
     * @var $infoEtudiant InfoEtudiant
     * @ORM\OneToOne(targetEntity="Masca\EtudiantBundle\Entity\InfoEtudiant", cascade={"persist","remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $infoEtudiant;

    /**
     * @var $sonClasse
     * @ORM\ManyToOne(targetEntity="Masca\EtudiantBundle\Entity\Classe", inversedBy="lyceens")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sonClasse;

    /**
     * @var boolean
     *
     * @ORM\Column(name="droit", type="boolean", options={"default":false})
     */
    private $droitInscription = false;

    /**
     * @var $sesNotes LyceenNote[]
     * @ORM\OneToMany(targetEntity="Masca\EtudiantBundle\Entity\LyceenNote", mappedBy="lyceen", cascade={"remove"})
     */
    private $sesNotes;

    /**
     * @var $sesEcolages FraisScolariteLyceen[]
     *
     * @ORM\OneToMany(targetEntity="Masca\EtudiantBundle\Entity\FraisScolariteLyceen", mappedBy="lyceen", cascade={"remove"})
     */
    private $sesEcolages;
    /**
     * @var $sesAbsences AbsenceLyceen[]
     * @ORM\OneToMany(targetEntity="Masca\EtudiantBundle\Entity\AbsenceLyceen", mappedBy="lyceen", cascade={"remove"})
     */
    private $sesAbsences;

    /**
     * @var $sesRetards RetardLyceen[]
     * @ORM\OneToMany(targetEntity="Masca\EtudiantBundle\Entity\RetardLyceen", mappedBy="lyceen", cascade={"remove"})
     */
    private $sesRetards;

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
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sesNotes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->sesAbsences = new \Doctrine\Common\Collections\ArrayCollection();
        $this->sesRetards = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add sesNote
     *
     * @param \Masca\EtudiantBundle\Entity\LyceenNote $sesNote
     *
     * @return Lyceen
     */
    public function addSesNote(\Masca\EtudiantBundle\Entity\LyceenNote $sesNote)
    {
        $this->sesNotes[] = $sesNote;
    
        return $this;
    }

    /**
     * Remove sesNote
     *
     * @param \Masca\EtudiantBundle\Entity\LyceenNote $sesNote
     */
    public function removeSesNote(\Masca\EtudiantBundle\Entity\LyceenNote $sesNote)
    {
        $this->sesNotes->removeElement($sesNote);
    }

    /**
     * Get sesNotes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSesNotes()
    {
        return $this->sesNotes;
    }

    /**
     * Add sesAbsence
     *
     * @param \Masca\EtudiantBundle\Entity\AbsenceLyceen $sesAbsence
     *
     * @return Lyceen
     */
    public function addSesAbsence(\Masca\EtudiantBundle\Entity\AbsenceLyceen $sesAbsence)
    {
        $this->sesAbsences[] = $sesAbsence;
    
        return $this;
    }

    /**
     * Remove sesAbsence
     *
     * @param \Masca\EtudiantBundle\Entity\AbsenceLyceen $sesAbsence
     */
    public function removeSesAbsence(\Masca\EtudiantBundle\Entity\AbsenceLyceen $sesAbsence)
    {
        $this->sesAbsences->removeElement($sesAbsence);
    }

    /**
     * Get sesAbsences
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSesAbsences()
    {
        return $this->sesAbsences;
    }

    /**
     * Add sesRetard
     *
     * @param \Masca\EtudiantBundle\Entity\RetardLyceen $sesRetard
     *
     * @return Lyceen
     */
    public function addSesRetard(\Masca\EtudiantBundle\Entity\RetardLyceen $sesRetard)
    {
        $this->sesRetards[] = $sesRetard;
    
        return $this;
    }

    /**
     * Remove sesRetard
     *
     * @param \Masca\EtudiantBundle\Entity\RetardLyceen $sesRetard
     */
    public function removeSesRetard(\Masca\EtudiantBundle\Entity\RetardLyceen $sesRetard)
    {
        $this->sesRetards->removeElement($sesRetard);
    }

    /**
     * Get sesRetards
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSesRetards()
    {
        return $this->sesRetards;
    }
    

    /**
     * Add sesEcolage
     *
     * @param \Masca\EtudiantBundle\Entity\FraisScolariteLyceen $sesEcolage
     *
     * @return Lyceen
     */
    public function addSesEcolage(\Masca\EtudiantBundle\Entity\FraisScolariteLyceen $sesEcolage)
    {
        $this->sesEcolages[] = $sesEcolage;

        return $this;
    }

    /**
     * Remove sesEcolage
     *
     * @param \Masca\EtudiantBundle\Entity\FraisScolariteLyceen $sesEcolage
     */
    public function removeSesEcolage(\Masca\EtudiantBundle\Entity\FraisScolariteLyceen $sesEcolage)
    {
        $this->sesEcolages->removeElement($sesEcolage);
    }

    /**
     * Get sesEcolages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSesEcolages()
    {
        return $this->sesEcolages;
    }

    /**
     * Set droitInscription
     *
     * @param boolean $droitInscription
     *
     * @return Lyceen
     */
    public function setDroitInscription($droitInscription)
    {
        $this->droitInscription = $droitInscription;

        return $this;
    }

    /**
     * Get droitInscription
     *
     * @return boolean
     */
    public function getDroitInscription()
    {
        return $this->droitInscription;
    }

    /**
     * Set nb
     *
     * @param string $nb
     *
     * @return Lyceen
     */
    public function setNb($nb)
    {
        $this->nb = $nb;

        return $this;
    }

    /**
     * Get nb
     *
     * @return string
     */
    public function getNb()
    {
        return $this->nb;
    }
}
