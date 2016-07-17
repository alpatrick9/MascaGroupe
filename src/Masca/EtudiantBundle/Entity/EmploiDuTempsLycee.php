<?php

namespace Masca\EtudiantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EmploiDuTempsLycee
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Masca\EtudiantBundle\Entity\EmploiDuTempsLyceeRepository")
 */
class EmploiDuTempsLycee
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
     * @ORM\Column(name="jourIndex", type="integer")
     */
    private $jourIndex;

    /**
     * @var integer
     *
     * @ORM\Column(name="heureIndex", type="integer")
     */
    private $heureIndex;

    /**
     * @var $matiere MatiereLycee
     *
     * @ORM\ManyToOne(targetEntity="Masca\EtudiantBundle\Entity\MatiereLycee", inversedBy="emploiDuTemps")
     * @ORM\JoinColumn(nullable=false)
     */
    private $matiere;

    /**
     * @var $classe Classe
     *
     * @ORM\ManyToOne(targetEntity="Masca\EtudiantBundle\Entity\Classe", inversedBy="emploiDuTemps")
     * @ORM\JoinColumn(nullable=false)
     */
    private $classe;


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
     * Set jourIndex
     *
     * @param integer $jourIndex
     * @return EmploiDuTempsLycee
     */
    public function setJourIndex($jourIndex)
    {
        $this->jourIndex = $jourIndex;
    
        return $this;
    }

    /**
     * Get jourIndex
     *
     * @return integer 
     */
    public function getJourIndex()
    {
        return $this->jourIndex;
    }

    /**
     * Set heureIndex
     *
     * @param integer $heureIndex
     * @return EmploiDuTempsLycee
     */
    public function setHeureIndex($heureIndex)
    {
        $this->heureIndex = $heureIndex;
    
        return $this;
    }

    /**
     * Get heureIndex
     *
     * @return integer 
     */
    public function getHeureIndex()
    {
        return $this->heureIndex;
    }

    /**
     * Set matiere
     *
     * @param \Masca\EtudiantBundle\Entity\MatiereLycee $matiere
     * @return EmploiDuTempsLycee
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
     * Set classe
     *
     * @param \Masca\EtudiantBundle\Entity\Classe $classe
     * @return EmploiDuTempsLycee
     */
    public function setClasse(\Masca\EtudiantBundle\Entity\Classe $classe)
    {
        $this->classe = $classe;
    
        return $this;
    }

    /**
     * Get classe
     *
     * @return \Masca\EtudiantBundle\Entity\Classe 
     */
    public function getClasse()
    {
        return $this->classe;
    }
}
