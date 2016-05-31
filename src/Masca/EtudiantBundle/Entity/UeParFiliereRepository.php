<?php

namespace Masca\EtudiantBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * UeParFiliereRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UeParFiliereRepository extends EntityRepository
{
    public function getRepartions($nbParPage, $page) {
        if($page < 1) {
            throw new \InvalidArgumentException('L\'argument $page ne peut être inférieur à 1 (valeur: "'.$page.'").');
        }

        $query = $this->createQueryBuilder('repartition')
            ->orderBy('repartition.id', 'DESC')
            ->getQuery();

        $query->setFirstResult(($page-1) * $nbParPage)->setMaxResults($nbParPage);
        return new Paginator($query);
    }
}
