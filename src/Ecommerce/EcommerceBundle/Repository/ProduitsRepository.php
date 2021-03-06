<?php

namespace Ecommerce\EcommerceBundle\Repository;

/**
 * ProduitsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProduitsRepository extends \Doctrine\ORM\EntityRepository
{
    public function byCategorie($categorie){
        $qb = $this->createQueryBuilder("u")
                    ->select("u")
                    ->where("u.categorie = :categorie")
                    ->andWhere("u.disponible = 1")
                    ->orderBy("u.id")
                    ->setParameter("categorie",$categorie);
        return $qb->getQuery()->getResult();
    }

    public function recherche($chaine){
        $qb = $this->createQueryBuilder('u')
            ->select('u')
            ->where('u.nom LIKE :chaine')
            ->orderBy('u.id')
            ->setParameter('chaine','%'.$chaine.'%');
        return $qb->getQuery()->getResult();
    }

    public function findIn($keys = array()){
        $qb = $this->createQueryBuilder('u')
            ->select('u')
            ->where('u.id IN (:array)')
            ->setParameter('array', $keys);

        return $qb->getQuery()->getResult();
    }

}
