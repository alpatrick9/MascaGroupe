<?php

namespace Masca\EtudiantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FraisScolarite
 *
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="idxUnique", columns={"mois", "annee", "lyceen_id"})})
 * @ORM\Entity(repositoryClass="Masca\EtudiantBundle\Entity\FraisScolariteLyceenRepository")
 */
class FraisScolariteLyceen
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
     * @ORM\Column(name="anneeScolaire", type="string",length=255)
     */
    private $anneeScolaire;

    /**
     * @var string
     *
     * @ORM\Column(name="mois", type="string", length=255)
     */
    private $mois;

    /**
     * @var integer
     *
     * @ORM\Column(name="annee", type="integer")
     */
    private $annee;

    /**
     * @var float
     *
     * @ORM\Column(name="montant", type="float")
     */
    private $montant;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", options={"default":false})
     */
    private $status;

    /**
     * @var $lyceen Lyceen
     *
     * @ORM\ManyToOne(targetEntity="Masca\EtudiantBundle\Entity\Lyceen", inversedBy="sesEcolages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lyceen;

    /**
     * @var $historiquePayements DatePayementEcolageLycee[]
     * @ORM\OneToMany(targetEntity="Masca\EtudiantBundle\Entity\DatePayementEcolageLycee", mappedBy="fraisScolariteLyceen", cascade={"remove"})
     */
    private $historiquePayements;

    /**
     * FraisScolariteLyceen constructor.
     */
    public function __construct()
    {
        $this->status = false;
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
     * Set annee
     *
     * @param string $anneeScolaire
     * @return FraisScolariteLyceen
     */
    public function setAnneeScolaire($anneeScolaire)
    {
        $this->anneeScolaire = $anneeScolaire;
    
        return $this;
    }

    /**
     * Get annee
     *
     * @return string
     */
    public function getAnneeScolaire()
    {
        return $this->anneeScolaire;
    }

    /**
     * Set mois
     *
     * @param string $mois
     * @return FraisScolariteLyceen
     */
    public function setMois($mois)
    {
        $this->mois = $mois;
    
        return $this;
    }

    /**
     * Get mois
     *
     * @return string 
     */
    public function getMois()
    {
        return $this->mois;
    }

    /**
     * Set montant
     *
     * @param float $montant
     * @return FraisScolariteLyceen
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
     * Set Lyceen
     *
     * @param \Masca\EtudiantBundle\Entity\Lyceen $lyceen
     * @return FraisScolariteLyceen
     */
    public function setLyceen(\Masca\EtudiantBundle\Entity\Lyceen $lyceen)
    {
        $this->lyceen = $lyceen;

        return $this;
    }

    /**
     * Get Lyceen
     *
     * @return \Masca\EtudiantBundle\Entity\Lyceen 
     */
    public function getLyceen()
    {
        return $this->lyceen;
    }

    /**
     * Set annee
     *
     * @param integer $annee
     * @return FraisScolariteLyceen
     */
    public function setAnnee($annee)
    {
        $this->annee = $annee;
    
        return $this;
    }

    /**
     * Get annee
     *
     * @return integer 
     */
    public function getAnnee()
    {
        return $this->annee;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return FraisScolariteLyceen
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return boolean 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Add historiquePayement
     *
     * @param \Masca\EtudiantBundle\Entity\DatePayementEcolageLycee $historiquePayement
     *
     * @return FraisScolariteLyceen
     */
    public function addHistoriquePayement(\Masca\EtudiantBundle\Entity\DatePayementEcolageLycee $historiquePayement)
    {
        $this->historiquePayements[] = $historiquePayement;
    
        return $this;
    }

    /**
     * Remove historiquePayement
     *
     * @param \Masca\EtudiantBundle\Entity\DatePayementEcolageLycee $historiquePayement
     */
    public function removeHistoriquePayement(\Masca\EtudiantBundle\Entity\DatePayementEcolageLycee $historiquePayement)
    {
        $this->historiquePayements->removeElement($historiquePayement);
    }

    /**
     * Get historiquePayements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHistoriquePayements()
    {
        return $this->historiquePayements;
    }
}
