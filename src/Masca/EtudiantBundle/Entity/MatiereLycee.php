<?php

namespace Masca\EtudiantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MatiereLycee
 *
 * @ORM\Table(name="matiere_lycee")
 * @ORM\Entity(repositoryClass="Masca\EtudiantBundle\Repository\MatiereLyceeRepository")
 */
class MatiereLycee
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
     * @ORM\Column(name="intitule", type="string", length=255, unique=true)
     */
    private $intitule;

    /**
     * @var $notes LyceenNote[]
     * @ORM\OneToMany(targetEntity="Masca\EtudiantBundle\Entity\LyceenNote", mappedBy="matiere", cascade={"remove"})
     */
    private $notes;

    /**
     * @var $emploiDuTemps EmploiDuTempsLycee[]
     * @ORM\OneToMany(targetEntity="Masca\EtudiantBundle\Entity\EmploiDuTempsLycee", mappedBy="matiere", cascade={"remove"})
     */
    private $emploiDuTemps;


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
     * Set intitule
     *
     * @param string $intitule
     *
     * @return MatiereLycee
     */
    public function setIntitule($intitule)
    {
        $this->intitule = $intitule;

        return $this;
    }

    /**
     * Get intitule
     *
     * @return string
     */
    public function getIntitule()
    {
        return $this->intitule;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->emploiDuTemps = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add emploiDuTemp
     *
     * @param \Masca\EtudiantBundle\Entity\EmploiDuTempsLycee $emploiDuTemp
     *
     * @return MatiereLycee
     */
    public function addEmploiDuTemp(\Masca\EtudiantBundle\Entity\EmploiDuTempsLycee $emploiDuTemp)
    {
        $this->emploiDuTemps[] = $emploiDuTemp;
    
        return $this;
    }

    /**
     * Remove emploiDuTemp
     *
     * @param \Masca\EtudiantBundle\Entity\EmploiDuTempsLycee $emploiDuTemp
     */
    public function removeEmploiDuTemp(\Masca\EtudiantBundle\Entity\EmploiDuTempsLycee $emploiDuTemp)
    {
        $this->emploiDuTemps->removeElement($emploiDuTemp);
    }

    /**
     * Get emploiDuTemps
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmploiDuTemps()
    {
        return $this->emploiDuTemps;
    }

    /**
     * Add note
     *
     * @param \Masca\EtudiantBundle\Entity\LyceenNote $note
     *
     * @return MatiereLycee
     */
    public function addNote(\Masca\EtudiantBundle\Entity\LyceenNote $note)
    {
        $this->notes[] = $note;
    
        return $this;
    }

    /**
     * Remove note
     *
     * @param \Masca\EtudiantBundle\Entity\LyceenNote $note
     */
    public function removeNote(\Masca\EtudiantBundle\Entity\LyceenNote $note)
    {
        $this->notes->removeElement($note);
    }

    /**
     * Get notes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotes()
    {
        return $this->notes;
    }
}
