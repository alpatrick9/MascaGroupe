<?php

namespace Masca\EtudiantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NoteUniv
 *
 * @ORM\Table(name="note_univ",uniqueConstraints={@ORM\UniqueConstraint(name="idxUnique", columns={"son_filiere_id", "matiere_id"})})
 * @ORM\Entity(repositoryClass="Masca\EtudiantBundle\Repository\NoteUnivRepository")
 */
class NoteUniv
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
     * @var int
     *
     * @ORM\Column(name="coefficient", type="integer")
     */
    private $coefficient;

    /**
     * @var float
     *
     * @ORM\Column(name="note", type="float")
     */
    private $note;

    /**
     * @var UniversitaireSonFiliere
     *
     * @ORM\ManyToOne(targetEntity="Masca\EtudiantBundle\Entity\UniversitaireSonFiliere")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sonFiliere;

    /**
     * @var MatiereParUeFiliere
     *
     * @ORM\ManyToOne(targetEntity="Masca\EtudiantBundle\Entity\MatiereParUeFiliere")
     * @ORM\JoinColumn(nullable=false)
     */
    private $matiere;

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
     * Set coefficient
     *
     * @param integer $coefficient
     *
     * @return NoteUniv
     */
    public function setCoefficient($coefficient)
    {
        $this->coefficient = $coefficient;

        return $this;
    }

    /**
     * Get coefficient
     *
     * @return int
     */
    public function getCoefficient()
    {
        return $this->coefficient;
    }

    /**
     * Set note
     *
     * @param float $note
     *
     * @return NoteUniv
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return float
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set sonFiliere
     *
     * @param \Masca\EtudiantBundle\Entity\UniversitaireSonFiliere $sonFiliere
     *
     * @return NoteUniv
     */
    public function setSonFiliere(\Masca\EtudiantBundle\Entity\UniversitaireSonFiliere $sonFiliere)
    {
        $this->sonFiliere = $sonFiliere;

        return $this;
    }

    /**
     * Get sonFiliere
     *
     * @return \Masca\EtudiantBundle\Entity\UniversitaireSonFiliere
     */
    public function getSonFiliere()
    {
        return $this->sonFiliere;
    }

    /**
     * Set matiere
     *
     * @param \Masca\EtudiantBundle\Entity\MatiereParUeFiliere $matiere
     *
     * @return NoteUniv
     */
    public function setMatiere(\Masca\EtudiantBundle\Entity\MatiereParUeFiliere $matiere)
    {
        $this->matiere = $matiere;

        return $this;
    }

    /**
     * Get matiere
     *
     * @return \Masca\EtudiantBundle\Entity\MatiereParUeFiliere
     */
    public function getMatiere()
    {
        return $this->matiere;
    }
}
