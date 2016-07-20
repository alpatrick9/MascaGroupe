<?php

namespace Masca\PersonnelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Masca\EtudiantBundle\Entity\Person;

/**
 * Status
 *
 * @ORM\Table(name="status")
 * @ORM\Entity(repositoryClass="Masca\PersonnelBundle\Repository\StatusRepository")
 */
class Status
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
     * @var bool
     *
     * @ORM\Column(name="permanent", type="boolean")
     */
    private $permanent;

    /**
     * @var $person Person
     * @ORM\ManyToOne(targetEntity="Masca\EtudiantBundle\Entity\Person", inversedBy="lesStatus")
     */
    private $person;


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
     * Set permanent
     *
     * @param boolean $permanent
     *
     * @return Status
     */
    public function setPermanent($permanent)
    {
        $this->permanent = $permanent;

        return $this;
    }

    /**
     * Get permanent
     *
     * @return bool
     */
    public function getPermanent()
    {
        return $this->permanent;
    }

    /**
     * Set person
     *
     * @param \Masca\EtudiantBundle\Entity\Person $person
     *
     * @return Status
     */
    public function setPerson(\Masca\EtudiantBundle\Entity\Person $person = null)
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
}
