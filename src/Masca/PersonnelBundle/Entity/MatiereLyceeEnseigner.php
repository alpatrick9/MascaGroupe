<?php

namespace Masca\PersonnelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Masca\EtudiantBundle\Entity\MatiereLycee;

/**
 * MatiereLyceeEnseigner
 *
 * @ORM\Table(name="matiere_lycee_enseigner", uniqueConstraints={@ORM\UniqueConstraint(name="idxUnique", columns={"matiere_id", "info_id"})})
 * @ORM\Entity(repositoryClass="Masca\PersonnelBundle\Repository\MatiereLyceeEnseignerRepository")
 */
class MatiereLyceeEnseigner
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
     * @var $matiere MatiereLycee
     * @ORM\ManyToOne(targetEntity="Masca\EtudiantBundle\Entity\MatiereLycee", inversedBy="lesEnseignants")
     */
    private $matiere;

    /**
     * @var $employer InfoVolumeHoraire
     * @ORM\ManyToOne(targetEntity="Masca\PersonnelBundle\Entity\InfoVolumeHoraire", inversedBy="sesMatieres")
     */
    private $info;

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
     * Set matiere
     *
     * @param \Masca\EtudiantBundle\Entity\MatiereLycee $matiere
     *
     * @return MatiereLyceeEnseigner
     */
    public function setMatiere(\Masca\EtudiantBundle\Entity\MatiereLycee $matiere = null)
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
     * Set info
     *
     * @param \Masca\PersonnelBundle\Entity\InfoVolumeHoraire $info
     *
     * @return MatiereLyceeEnseigner
     */
    public function setInfo(\Masca\PersonnelBundle\Entity\InfoVolumeHoraire $info = null)
    {
        $this->info = $info;

        return $this;
    }

    /**
     * Get info
     *
     * @return \Masca\PersonnelBundle\Entity\InfoVolumeHoraire
     */
    public function getInfo()
    {
        return $this->info;
    }
}
