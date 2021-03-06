<?php

namespace Masca\PersonnelBundle\Repository;

use Masca\PersonnelBundle\Entity\Employer;

/**
 * InfoVolumeHoraireRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class InfoVolumeHoraireRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllPostHoraire(Employer $employer)
    {
        $query = $this->createQueryBuilder('info')
            ->leftJoin('info.status', 'status')
            ->leftJoin('status.employer', 'employer')
            ->where('employer = :employer')->setParameter('employer', $employer);
        return $query->getQuery()->getResult();
    }
}
