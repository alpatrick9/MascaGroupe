<?php

namespace Masca\EtudiantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LyceenNote
 *
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="idxUnique", columns={"lyceen_id", "matiere_id"})})
 * @ORM\Entity(repositoryClass="Masca\EtudiantBundle\Entity\LyceenNoteRepository")
 */
class LyceenNote
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
     * @var integer
     *
     * @ORM\Column(name="coefficient", type="integer")
     */
    private $coefficient;

    /**
     * @var float
     *
     * @ORM\Column(name="noteTrimestre1", type="float", nullable=true)
     */
    private $noteTrimestre1;

    /**
     * @var float
     * @ORM\Column(name="noteTrimestre2", type="float", nullable = true)
     */
    private $noteTrimestre2;

    /**
     * @var float
     * @ORM\Column(name="noteTrimestre3", type="float", nullable = true)
     */
    private $noteTrimestre3;

    /**
     * @var $lyceen Lyceen
     *
     * @ORM\ManyToOne(targetEntity="Masca\EtudiantBundle\Entity\Lyceen", inversedBy="sesNotes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lyceen;

    /**
     * @var $matiere MatiereLycee
     *
     * @ORM\ManyToOne(targetEntity="Masca\EtudiantBundle\Entity\MatiereLycee", inversedBy="notes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $matiere;

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
     * Set coefficient
     *
     * @param integer $coefficient
     * @return LyceenNote
     */
    public function setCoefficient($coefficient)
    {
        $this->coefficient = $coefficient;
    
        return $this;
    }

    /**
     * Get coefficient
     *
     * @return integer 
     */
    public function getCoefficient()
    {
        return $this->coefficient;
    }

    /**
     * Set note
     *
     * @param float $noteTrimestre1
     * @return LyceenNote
     */
    public function setNoteTrimestre1($noteTrimestre1)
    {
        $this->noteTrimestre1 = $noteTrimestre1;
    
        return $this;
    }

    /**
     * Get note
     *
     * @return float
     */
    public function getNoteTrimestre1()
    {
        return $this->noteTrimestre1;
    }

    /**
     * Set lyceen
     *
     * @param \Masca\EtudiantBundle\Entity\Lyceen $lyceen
     * @return LyceenNote
     */
    public function setLyceen(\Masca\EtudiantBundle\Entity\Lyceen $lyceen)
    {
        $this->lyceen = $lyceen;
    
        return $this;
    }

    /**
     * Get lyceen
     *
     * @return \Masca\EtudiantBundle\Entity\Lyceen 
     */
    public function getLyceen()
    {
        return $this->lyceen;
    }

    /**
     * Set matiere
     *
     * @param \Masca\EtudiantBundle\Entity\MatiereLycee $matiere
     * @return LyceenNote
     */
    public function setMatiere(\Masca\EtudiantBundle\Entity\MatiereLycee $matiere)
    {
        $this->matiere = $matiere;
    
        return $this;
    }

    /**
     * Get matiere
     *
     * @return \Masca\EtudiantBundle\Entity\MatiereLycee
     */
    public function getMatiere()
    {
        return $this->matiere;
    }

    /**
     * Set noteTrimestre2
     *
     * @param float $noteTrimestre2
     *
     * @return LyceenNote
     */
    public function setNoteTrimestre2($noteTrimestre2)
    {
        $this->noteTrimestre2 = $noteTrimestre2;

        return $this;
    }

    /**
     * Get noteTrimestre2
     *
     * @return float
     */
    public function getNoteTrimestre2()
    {
        return $this->noteTrimestre2;
    }

    /**
     * Set noteTrimestre3
     *
     * @param float $noteTrimestre3
     *
     * @return LyceenNote
     */
    public function setNoteTrimestre3($noteTrimestre3)
    {
        $this->noteTrimestre3 = $noteTrimestre3;

        return $this;
    }

    /**
     * Get noteTrimestre3
     *
     * @return float
     */
    public function getNoteTrimestre3()
    {
        return $this->noteTrimestre3;
    }
}
