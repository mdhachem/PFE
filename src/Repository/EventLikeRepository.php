<?php

namespace App\Repository;

use App\Entity\EventLike;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EventLike|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventLike|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventLike[]    findAll()
 * @method EventLike[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventLikeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EventLike::class);
    }

    // /**
    //  * @return EventLike[] Returns an array of EventLike objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EventLike
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
