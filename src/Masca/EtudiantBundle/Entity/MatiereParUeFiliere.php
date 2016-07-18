<?php

namespace Masca\EtudiantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MatierParUeFiliere
 *
 * @ORM\Table(name="matier_par_ue_filiere", uniqueConstraints={@ORM\UniqueConstraint(name="idxUnique", columns={"ue_par_filiere_id", "matiere_id"})})
 * @ORM\Entity(repositoryClass="Masca\EtudiantBundle\Repository\MatierParUeFiliereRepository")
 */
class MatiereParUeFiliere
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
     * @var $ueParFiliere UeParFiliere
     *
     * @ORM\ManyToOne(targetEntity="Masca\EtudiantBundle\Entity\UeParFiliere", inversedBy="matieres")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ueParFiliere;

    /**
     * @var $matiere Matiere
     *
     * @ORM\ManyToOne(targetEntity="Masca\EtudiantBundle\Entity\Matiere", inversedBy="lesUeFilieres")
     * @ORM\JoinColumn(nullable=false)
     */
    private $matiere;

    /**
     * @var $sesNotes NoteUniv[]
     * @ORM\OneToMany(targetEntity="Masca\EtudiantBundle\Entity\NoteUniv", mappedBy="matiere", cascade={"remove"})
     */
    private $sesNotes;


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
     * Set ueParFiliere
     *
     * @param \Masca\EtudiantBundle\Entity\UeParFiliere $ueParFiliere
     *
     * @return MatiereParUeFiliere
     */
    public function setUeParFiliere(\Masca\EtudiantBundle\Entity\UeParFiliere $ueParFiliere)
    {
        $this->ueParFiliere = $ueParFiliere;

        return $this;
    }

    /**
     * Get ueParFiliere
     *
     * @return \Masca\EtudiantBundle\Entity\UeParFiliere
     */
    public function getUeParFiliere()
    {
        return $this->ueParFiliere;
    }

    /**
     * Set matiere
     *
     * @param \Masca\EtudiantBundle\Entity\Matiere $matiere
     *
     * @return MatiereParUeFiliere
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
     * Constructor
     */
    public function __construct()
    {
        $this->sesNotes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add sesNote
     *
     * @param \Masca\EtudiantBundle\Entity\NoteUniv $sesNote
     *
     * @return MatiereParUeFiliere
     */
    public function addSesNote(\Masca\EtudiantBundle\Entity\NoteUniv $sesNote)
    {
        $this->sesNotes[] = $sesNote;

        return $this;
    }

    /**
     * Remove sesNote
     *
     * @param \Masca\EtudiantBundle\Entity\NoteUniv $sesNote
     */
    public function removeSesNote(\Masca\EtudiantBundle\Entity\NoteUniv $sesNote)
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
}
