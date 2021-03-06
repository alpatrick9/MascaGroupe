<?php

namespace Masca\EtudiantBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * FraisScolariteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FraisScolariteLyceenRepository extends EntityRepository
{
    public function statusEcolage(Lyceen $lyceen) {
        $query = $this->createQueryBuilder('ecolage')
            ->where('ecolage.lyceen = :lyceen')->setParameter('lyceen',$lyceen)
            ->andWhere('ecolage.status = :status')->setParameter('status', 0)
            ->getQuery();
        return $query->getOneOrNullResult() != null;
    }
}
