<?php

namespace App\Repository;

use App\Entity\Service;
use App\Entity\SearchEntity\ServiceSearch;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Service|null find($id, $lockMode = null, $lockVersion = null)
 * @method Service|null findOneBy(array $criteria, array $orderBy = null)
 * @method Service[]    findAll()
 * @method Service[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Service::class);
    }

    public function findAllService(ServiceSearch $search)
    {
        $query = $this->createQueryBuilder('p');

        if ($search->getName()) {
            $query = $query->andWhere('p.name like :name ')
                ->setParameter('name', '%' . $search->getName() . '%');
        }

        return $query->getQuery();
    }

    public function countServices()
    {
        return $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }



    // /**
    //  * @return Service[] Returns an array of Service objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Service
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
