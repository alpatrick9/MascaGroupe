<?php

namespace Masca\EtudiantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UeParFiliere
 *
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="idxUnique", columns={"ue_id", "filiere_id", "niveau_id"})})
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
     * @ORM\ManyToOne(targetEntity="Masca\EtudiantBundle\Entity\Ue", inversedBy="lesFilieres")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ue;

    /**
     * @var $filiere Filiere
     *
     * @ORM\ManyToOne(targetEntity="Masca\EtudiantBundle\Entity\Filiere", inversedBy="lesUes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $filiere;

    /**
     * @var $niveau NiveauEtude
     *
     * @ORM\ManyToOne(targetEntity="Masca\EtudiantBundle\Entity\NiveauEtude", inversedBy="lesUes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $niveau;

    /**
     * @var $matieres MatiereParUeFiliere[]
     * @ORM\OneToMany(targetEntity="Masca\EtudiantBundle\Entity\MatiereParUeFiliere", mappedBy="ueParFiliere", cascade={"remove"})
     */
    private $matieres;
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

    /**
     * Set niveau
     *
     * @param \Masca\EtudiantBundle\Entity\NiveauEtude $niveau
     *
     * @return UeParFiliere
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
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->matieres = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add matiere
     *
     * @param \Masca\EtudiantBundle\Entity\MatiereParUeFiliere $matiere
     *
     * @return UeParFiliere
     */
    public function addMatiere(\Masca\EtudiantBundle\Entity\MatiereParUeFiliere $matiere)
    {
        $this->matieres[] = $matiere;

        return $this;
    }

    /**
     * Remove matiere
     *
     * @param \Masca\EtudiantBundle\Entity\MatiereParUeFiliere $matiere
     */
    public function removeMatiere(\Masca\EtudiantBundle\Entity\MatiereParUeFiliere $matiere)
    {
        $this->matieres->removeElement($matiere);
    }

    /**
     * Get matieres
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMatieres()
    {
        return $this->matieres;
    }
}
