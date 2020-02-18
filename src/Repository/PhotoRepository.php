<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Photo;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Photo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Photo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Photo[]    findAll()
 * @method Photo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhotoRepository extends ServiceEntityRepository
{


    private $repo;

    public function __construct(RegistryInterface $registry, PlanRepository $repo)
    {
        parent::__construct($registry, Photo::class);
        $this->repo = $repo;
    }


    public function findPlanByUser(User $user)
    {
        $query = $this->createQueryBuilder('p')
            ->where('p.plan IN (:idplan) ')
            ->setParameter('idplan', $this->repo->userPlanId($user));

        return $query->getQuery();
    }

    public function countPhotoByUser(User $user)
    {

        return $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->where('p.plan IN (:idplan) ')
            ->setParameter('idplan', $this->repo->userPlanId($user))
            ->getQuery()
            ->getSingleScalarResult();
    }

    // /**
    //  * @return Photo[] Returns an array of Photo objects
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
    public function findOneBySomeField($value): ?Photo
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
