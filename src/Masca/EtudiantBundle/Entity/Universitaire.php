<?php

namespace Masca\EtudiantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Universitaire
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Masca\EtudiantBundle\Entity\UniversitaireRepository")
 */
class Universitaire
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
     * @ORM\Column(name="serieBacc", type="string", length=255)
     */
    private $serieBacc;

    /**
     * @var $person Person
     *
     * @ORM\OneToOne(targetEntity="Masca\EtudiantBundle\Entity\Person", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $person;

    /**
     * @var $infoEtudiant InfoEtudiant
     *
     * @ORM\OneToOne(targetEntity="Masca\EtudiantBundle\Entity\InfoEtudiant", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $infoEtudiant;


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
     * Set serieBacc
     *
     * @param string $serieBacc
     * @return Universitaire
     */
    public function setSerieBacc($serieBacc)
    {
        $this->serieBacc = $serieBacc;
    
        return $this;
    }

    /**
     * Get serieBacc
     *
     * @return string 
     */
    public function getSerieBacc()
    {
        return $this->serieBacc;
    }

    /**
     * Set person
     *
     * @param \Masca\EtudiantBundle\Entity\Person $person
     * @return Universitaire
     */
    public function setPerson(\Masca\EtudiantBundle\Entity\Person $person)
    {
        $this->person = $person;
    
        return $this;
    }

    /**
     * Get person
     *
     * @return \Masca\EtudiantBundle\Entity\Person 
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Set infoEtudiant
     *
     * @param \Masca\EtudiantBundle\Entity\InfoEtudiant $infoEtudiant
     * @return Universitaire
     */
    public function setInfoEtudiant(\Masca\EtudiantBundle\Entity\InfoEtudiant $infoEtudiant)
    {
        $this->infoEtudiant = $infoEtudiant;
    
        return $this;
    }

    /**
     * Get infoEtudiant
     *
     * @return \Masca\EtudiantBundle\Entity\InfoEtudiant 
     */
    public function getInfoEtudiant()
    {
        return $this->infoEtudiant;
    }
}