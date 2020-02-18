<?php

namespace App\Repository;

use App\Entity\Rating;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Rating|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rating|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rating[]    findAll()
 * @method Rating[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RatingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Rating::class);
    }

    public function findAvgRating($id)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT Avg(a.rating) as sum
                FROM App\Entity\Rating a WHERE a.plan = :id'
            )
            ->setParameter('id', $id)
            ->getSingleScalarResult();
    }

    public function findStarOne($id)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT COUNT(a) as count
                FROM App\Entity\Rating a Where a.plan = :id AND a.rating = 1'
            )
            ->setParameter('id', $id)
            ->getSingleScalarResult();
    }

    public function findStarTwo($id)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT COUNT(a) as count
                FROM App\Entity\Rating a Where a.plan = :id AND a.rating = 2'
            )
            ->setParameter('id', $id)
            ->getSingleScalarResult();
    }

    public function findStarThree($id)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT COUNT(a) as count
                FROM App\Entity\Rating a Where a.plan = :id AND a.rating = 3'
            )
            ->setParameter('id', $id)
            ->getSingleScalarResult();
    }

    public function findStarFour($id)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT COUNT(a) as count
                FROM App\Entity\Rating a Where a.plan = :id AND a.rating = 4'
            )
            ->setParameter('id', $id)
            ->getSingleScalarResult();
    }

    public function findStarFive($id)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT COUNT(a) as count
                FROM App\Entity\Rating a Where a.plan = :id AND a.rating = 5'
            )
            ->setParameter('id', $id)
            ->getSingleScalarResult();
    }

    // /**
    //  * @return Rating[] Returns an array of Rating objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Rating
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
