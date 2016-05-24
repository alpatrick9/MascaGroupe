<?php

namespace Masca\EtudiantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EmploiDuTempsUniv
 *
 * @ORM\Table(name="emploi_du_temps_univ")
 * @ORM\Entity(repositoryClass="Masca\EtudiantBundle\Repository\EmploiDuTempsUnivRepository")
 */
class EmploiDuTempsUniv
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
     * @ORM\Column(name="jourIndex", type="integer")
     */
    private $jourIndex;

    /**
     * @var int
     *
     * @ORM\Column(name="heureIndex", type="integer")
     */
    private $heureIndex;

    /**
     * @var FiliereParNiveau
     *
     * @ORM\ManyToOne(targetEntity="Masca\EtudiantBundle\Entity\FiliereParNiveau", inversedBy="emploiDuTemps")
     * @ORM\JoinColumn(nullable=false)
     */
    private $filiereParNiveau;

    /**
     * @var Matiere
     *
     * @ORM\ManyToOne(targetEntity="Masca\EtudiantBundle\Entity\Matiere")
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
     * Set jourIndex
     *
     * @param integer $jourIndex
     *
     * @return EmploiDuTempsUniv
     */
    public function setJourIndex($jourIndex)
    {
        $this->jourIndex = $jourIndex;

        return $this;
    }

    /**
     * Get jourIndex
     *
     * @return int
     */
    public function getJourIndex()
    {
        return $this->jourIndex;
    }

    /**
     * Set heureIndex
     *
     * @param integer $heureIndex
     *
     * @return EmploiDuTempsUniv
     */
    public function setHeureIndex($heureIndex)
    {
        $this->heureIndex = $heureIndex;

        return $this;
    }

    /**
     * Get heureIndex
     *
     * @return int
     */
    public function getHeureIndex()
    {
        return $this->heureIndex;
    }

    /**
     * Set matiere
     *
     * @param \Masca\EtudiantBundle\Entity\Matiere $matiere
     *
     * @return EmploiDuTempsUniv
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
     * Set filiereParNiveau
     *
     * @param \Masca\EtudiantBundle\Entity\FiliereParNiveau $filiereParNiveau
     *
     * @return EmploiDuTempsUniv
     */
    public function setFiliereParNiveau(\Masca\EtudiantBundle\Entity\FiliereParNiveau $filiereParNiveau)
    {
        $this->filiereParNiveau = $filiereParNiveau;

        return $this;
    }

    /**
     * Get filiereParNiveau
     *
     * @return \Masca\EtudiantBundle\Entity\FiliereParNiveau
     */
    public function getFiliereParNiveau()
    {
        return $this->filiereParNiveau;
    }
}