<?php

namespace Masca\PersonnelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Masca\EtudiantBundle\Entity\Person;
use Symfony\Component\Validator\Constraints\DateTime;

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
     * @var string
     *
     * @ORM\Column(name="type_poste", type="string", length=255)
     */
    private $typePoste;

    /**
     * @var $dateEmbauche \DateTime
     * @ORM\Column(name="dateEmbauche", type="date")
     */
    private $dateEmbauche;

    /**
     * @var $employer Employer
     * @ORM\ManyToOne(targetEntity="Masca\PersonnelBundle\Entity\Employer", cascade={"persist"})
     */
    private $employer;


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
     * Set libelePoste
     *
     * @param string $typePoste
     *
     * @return Status
     */
    public function setTypePoste($typePoste)
    {
        $this->typePoste = $typePoste;

        return $this;
    }

    /**
     * Get libelePoste
     *
     * @return string
     */
    public function getTypePoste()
    {
        return $this->typePoste;
    }

    /**
     * Set employer
     *
     * @param \Masca\PersonnelBundle\Entity\Employer $employer
     *
     * @return Status
     */
    public function setEmployer(\Masca\PersonnelBundle\Entity\Employer $employer = null)
    {
        $this->employer = $employer;

        return $this;
    }

    /**
     * Get employer
     *
     * @return \Masca\PersonnelBundle\Entity\Employer
     */
    public function getEmployer()
    {
        return $this->employer;
    }

    /**
     * Set dateEmbauche
     *
     * @param \DateTime $dateEmbauche
     *
     * @return Status
     */
    public function setDateEmbauche($dateEmbauche)
    {
        $this->dateEmbauche = $dateEmbauche;

        return $this;
    }

    /**
     * Get dateEmbauche
     *
     * @return \DateTime
     */
    public function getDateEmbauche()
    {
        return $this->dateEmbauche;
    }
}
