<?php

namespace Pages\PagesBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * PagesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PagesRepository extends EntityRepository
{
    public function findAllPath(){
        $qb = $this->createQueryBuilder("u")
            ->select("u.id")
            ->addSelect("u.titre");

        return $qb->getQuery()->getResult();
    }
}
