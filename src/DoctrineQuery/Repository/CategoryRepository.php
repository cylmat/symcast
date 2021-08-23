<?php

namespace DoctrineQuery\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineQuery\Entity\Category;

class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * doctrine:query:dql "select cat FROM DoctrineQuery\Entity\Category cat"
     */

    public function findWithFortunesJoin(string $id): ?Category
    {
        return $this->createQueryBuilder('cat')
            ->leftJoin('cat.fortuneCookies', 'fc')
            ->addSelect('fc')
            ->andWhere('cat.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function search(string $term): array
    {
        return $this->createQueryBuilder('cat')
            // JOIN
            ->leftJoin('cat.fortuneCookies', 'fc') //oneToMany side

            // avoid 2 queries: SELECT CATEGORY LEFT JOIN COOKIE -AND- SELECT ALL COOKIES FROM CATEGORY
            ->addSelect('fc') //select all infos from both sides
            
            ->andWhere("cat.name LIKE :searchTerm 
                OR cat.iconKey LIKe :searchTerm
                OR fc.fortune LIKE :searchTerm")
            ->setParameter('searchTerm', "%$term%")
            
            ->getQuery()
            ->execute();
    }
    
    public function findAllOrdered()
    {
        $qb = $this->createQueryBuilder('cat');
            //->leftJoin('cat.fortuneCookies', 'fc') //oneToMany side
            //->addSelect('fc') 
        $this->addFortuneCookieJoinAndSelect($qb); 
        $qb->addOrderBy('cat.name', 'ASC');
        $query = $qb->getQuery();

        return $query->execute();
    }

    public function findAllOrderedBis()
    {
        $dql = "SELECT cat FROM ".Category::class." cat";
        $query = $this->getEntityManager()->createQuery($dql);

        return $query->execute();
    }

    // avoid duplication
    private function addFortuneCookieJoinAndSelect(QueryBuilder $qb)
    {
        return $qb->leftJoin('cat.fortuneCookies', 'fc')
            ->addSelect('fc');
    }
}