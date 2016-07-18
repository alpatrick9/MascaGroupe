<?php

namespace Masca\EtudiantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FraisScolariteUniv
 *
 * @ORM\Table(name="frais_scolarite_univ", uniqueConstraints={@ORM\UniqueConstraint(name="idxUnique", columns={"mois", "annee", "univ_son_filiere_id"})})
 * @ORM\Entity(repositoryClass="Masca\EtudiantBundle\Repository\FraisScolariteUnivRepository")
 */
class FraisScolariteUniv
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
     * @var string
     *
     * @ORM\Column(name="mois", type="string", length=255)
     */
    private $mois;

    /**
     * @var int
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
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean", options={"default":false})
     */
    private $status;

    /**
     * @var $univSonFiliere UniversitaireSonFiliere
     *
     * @ORM\ManyToOne(targetEntity="Masca\EtudiantBundle\Entity\UniversitaireSonFiliere", inversedBy="sesEcolages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $univSonFiliere;

    /**
     * @var $historiquePayements DatePayementEcolageUniv[]
     * @ORM\OneToMany(targetEntity="Masca\EtudiantBundle\Entity\DatePayementEcolageUniv", mappedBy="fraisScolariteUniv", cascade={"remove"})
     */
    private $historiquePayements;

    /**
     * FraisScolariteUniv constructor.
     */
    public function __construct()
    {
        $this->status = false;
    }

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
     * Set mois
     *
     * @param string $mois
     *
     * @return FraisScolariteUniv
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
     * Set annee
     *
     * @param integer $annee
     *
     * @return FraisScolariteUniv
     */
    public function setAnnee($annee)
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * Get annee
     *
     * @return int
     */
    public function getAnnee()
    {
        return $this->annee;
    }

    /**
     * Set montant
     *
     * @param float $montant
     *
     * @return FraisScolariteUniv
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
     * Set status
     *
     * @param boolean $status
     *
     * @return FraisScolariteUniv
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return bool
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set univSonFiliere
     *
     * @param \Masca\EtudiantBundle\Entity\UniversitaireSonFiliere $univSonFiliere
     *
     * @return FraisScolariteUniv
     */
    public function setUnivSonFiliere(\Masca\EtudiantBundle\Entity\UniversitaireSonFiliere $univSonFiliere)
    {
        $this->univSonFiliere = $univSonFiliere;

        return $this;
    }

    /**
     * Get univSonFiliere
     *
     * @return \Masca\EtudiantBundle\Entity\UniversitaireSonFiliere
     */
    public function getUnivSonFiliere()
    {
        return $this->univSonFiliere;
    }

    /**
     * Add historiquePayement
     *
     * @param \Masca\EtudiantBundle\Entity\DatePayementEcolageUniv $historiquePayement
     *
     * @return FraisScolariteUniv
     */
    public function addHistoriquePayement(\Masca\EtudiantBundle\Entity\DatePayementEcolageUniv $historiquePayement)
    {
        $this->historiquePayements[] = $historiquePayement;

        return $this;
    }

    /**
     * Remove historiquePayement
     *
     * @param \Masca\EtudiantBundle\Entity\DatePayementEcolageUniv $historiquePayement
     */
    public function removeHistoriquePayement(\Masca\EtudiantBundle\Entity\DatePayementEcolageUniv $historiquePayement)
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
