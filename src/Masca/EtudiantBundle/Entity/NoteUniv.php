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
     * @var float
     * @ORM\Column(name="note_ef", type="float", nullable=true, options={"default":0})
     */
    private $noteEF;

    /**
     * @var float
     * @ORM\Column(name="note_fc", type="float", nullable=true, options={"default":0})
     */
    private $noteFC;

    /**
     * @var float
     * @ORM\Column(name="note_nj", type="float", nullable=true, options={"default":0})
     */
    private $noteNJ;

    /**
     * @var int
     *
     * @ORM\Column(name="coefficient", type="integer", options={"default":1})
     */
    private $coefficient;

    /**
     * @var float
     *
     * @ORM\Column(name="moyenne", type="float", options={"default": 0})
     */
    private $moyenne;

    /**
     * @var UniversitaireSonFiliere
     *
     * @ORM\ManyToOne(targetEntity="Masca\EtudiantBundle\Entity\UniversitaireSonFiliere", inversedBy="sesNotes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sonFiliere;

    /**
     * @var MatiereParUeFiliere
     *
     * @ORM\ManyToOne(targetEntity="Masca\EtudiantBundle\Entity\MatiereParUeFiliere", inversedBy="sesNotes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $matiere;

    /**
     * NoteUniv constructor.
     */
    public function __construct()
    {
        $this->noteEF = 0;
        $this->noteFC = 0;
        $this->noteNJ = 0;
        $this->coefficient = 1;
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

    /**
     * Set noteEF
     *
     * @param float $noteEF
     *
     * @return NoteUniv
     */
    public function setNoteEF($noteEF)
    {
        $this->noteEF = $noteEF;

        return $this;
    }

    /**
     * Get noteEF
     *
     * @return float
     */
    public function getNoteEF()
    {
        return $this->noteEF;
    }

    /**
     * Set noteFC
     *
     * @param float $noteFC
     *
     * @return NoteUniv
     */
    public function setNoteFC($noteFC)
    {
        $this->noteFC = $noteFC;

        return $this;
    }

    /**
     * Get noteFC
     *
     * @return float
     */
    public function getNoteFC()
    {
        return $this->noteFC;
    }

    /**
     * Set noteNJ
     *
     * @param float $noteNJ
     *
     * @return NoteUniv
     */
    public function setNoteNJ($noteNJ)
    {
        $this->noteNJ = $noteNJ;

        return $this;
    }

    /**
     * Get noteNJ
     *
     * @return float
     */
    public function getNoteNJ()
    {
        return $this->noteNJ;
    }


    /**
     * Set moyenne
     *
     * @param float $moyenne
     *
     * @return NoteUniv
     */
    public function setMoyenne($moyenne)
    {
        $this->moyenne = $moyenne;

        return $this;
    }

    /**
     * Get moyenne
     *
     * @return float
     */
    public function getMoyenne()
    {
        return $this->moyenne;
    }
}
