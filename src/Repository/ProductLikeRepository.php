<?php

namespace App\Repository;

use App\Entity\ProductLike;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ProductLike|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductLike|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductLike[]    findAll()
 * @method ProductLike[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductLikeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ProductLike::class);
    }

    // /**
    //  * @return ProductLike[] Returns an array of ProductLike objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProductLike
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
