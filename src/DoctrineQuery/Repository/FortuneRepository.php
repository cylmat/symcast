<?php

namespace DoctrineQuery\Repository;

use DoctrineQuery\Entity\FortuneCookie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineQuery\Entity\Category;

class FortuneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FortuneCookie::class);
    }

    public function countNumberPrintedForCategory(Category $category)
    {
        return $this->createQueryBuilder('fc')
            ->innerJoin('fc.category', 'cat')
            ->andWhere('fc.category = :category')
            ->setParameter('category', $category)
            ->select('SUM(fc.numberPrinted) as fortunesPrinted, AVG(fc.numberPrinted) as fortunesAverage, cat.name')
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function countNumberPrintedForCategoryUniq(Category $category)
    {
        // Native Sql sample
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = 'SELECT * FROM fortune_cookie';
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery();
        $stmt->fetch();
        // -

        return $this->createQueryBuilder('fc')
            ->andWhere('fc.category = :category')
            ->setParameter('category', $category)
            ->select('SUM(fc.numberPrinted) as fortunesPrinted')
            ->getQuery()
            ->getSingleScalarResult(); //->getOneOrNullResult();
    }
}