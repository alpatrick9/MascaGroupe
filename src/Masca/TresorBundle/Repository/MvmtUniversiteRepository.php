<?php

namespace Masca\TresorBundle\Repository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * MvmtUniversiteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MvmtUniversiteRepository extends \Doctrine\ORM\EntityRepository
{
    public function getMouvements($nbParPage, $page) {
        if($page < 1) {
            throw new \InvalidArgumentException('L\'argument $page ne peut être inférieur à 1 (valeur: "'.$page.'").');
        }

        $query = $this->createQueryBuilder('mvmt')
            ->orderBy('mvmt.date', 'DESC')
            ->getQuery();

        $query->setFirstResult(($page-1) * $nbParPage)->setMaxResults($nbParPage);
        return new Paginator($query);
    }

    public function deleteMouvement($yearMin) {
        $query = $this->createQueryBuilder('mvmt')
            ->delete()
            ->where('YEAR(mvmt.date) <= :min')->setParameter('min', $yearMin)
            ->getQuery();
        $query->execute();
    }
}
