<?php

namespace App\Repository;

use App\Entity\Governorate;
use App\Entity\SearchEntity\GovernorateSearch;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Governorate|null find($id, $lockMode = null, $lockVersion = null)
 * @method Governorate|null findOneBy(array $criteria, array $orderBy = null)
 * @method Governorate[]    findAll()
 * @method Governorate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GovernorateRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Governorate::class);
    }

    public function findAllGovernorate(GovernorateSearch $search)
    {
        $query = $this->createQueryBuilder('p');

        if ($search->getName()) {
            $query = $query->andWhere('p.name like :name ')
                ->setParameter('name', '%' . $search->getName() . '%');
        }

        return $query->getQuery();
    }

    public function countGov()
    {
        return $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findEntitiesByString($str)
    {

        return $this->createQueryBuilder('p')
            ->Where('p.name like :name ')
            ->setParameter('name', '%' . $str . '%')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Governorate[] Returns an array of Governorate objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Governorate
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
