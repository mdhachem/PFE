<?php

namespace App\Repository;

use App\Entity\Plan;
use App\Entity\User;
use Doctrine\ORM\Query;
use App\Repository\CityRepository;
use App\Entity\SearchEntity\PlanSearch;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Plan|null find($id, $lockMode = null, $lockVersion = null)
 * @method Plan|null findOneBy(array $criteria, array $orderBy = null)
 * @method Plan[]    findAll()
 * @method Plan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlanRepository extends ServiceEntityRepository
{

    private $repoCity;

    public function __construct(RegistryInterface $registry, CityRepository $repoCity)
    {
        parent::__construct($registry, Plan::class);
        $this->repoCity = $repoCity;
    }


    public function findByName($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.name like :query')
            ->setParameter('query', "%" . $value . "%")
            // ->orderBy('c.id', 'ASC')
            // ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function countPlan()
    {
        return $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findAllPlan(PlanSearch $search)
    {
        $query = $this->createQueryBuilder('p');

        if ($search->getName()) {
            $query = $query->andWhere('p.name like :name ')
                ->setParameter('name', '%' . $search->getName() . '%');
        }

        return $query->getQuery();
    }

    public function findEntitiesByString($str)
    {

        return $this->createQueryBuilder('p')
            ->Where('p.name like :name ')
            ->setParameter('name', '%' . $str . '%')
            ->getQuery()
            ->getResult();
    }

    public function findPlanByUser(User $user, PlanSearch $search)
    {
        $query = $this->createQueryBuilder('p')
            ->where('p.user =:user')
            ->setParameter('user', $user);

        if ($search->getName()) {
            $query = $query->andWhere('p.name like :name')
                ->setParameter('name', '%' . $search->getName() . '%');
        }

        return $query->getQuery();
    }


    public function countPlanByUser(User $user)
    {
        return $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->where('p.user =:user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function userPlanId(User $user)
    {
        return $this->createQueryBuilder('p')
            ->select('p.id')
            ->where('p.user =:user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    public function findPlanByString($plan, $place, $category): Query
    {
        $query = $this->createQueryBuilder('p');


        if ($plan) {
            $query = $query->andWhere('p.name like :plan ')
                ->setParameter('plan', '%' . $plan . '%');
        }

        if ($place) {

            $id = $this->repoCity->createQueryBuilder('g')
                ->select('g.id')
                ->where('g.name Like :place')
                ->setParameter('place', '%' . $place . '%')
                ->getQuery()
                ->getSingleScalarResult();

            $query = $query->andWhere('p.city = :place ')
                ->setParameter('place', $id);
        }

        if ($category) {
            $query = $query->innerJoin("p.category", "c")
                ->andWhere('c.id = :cat ')
                ->setParameter('cat', $category);
        }

        return $query->getQuery();
    }

    // /**
    //  * @return Plan[] Returns an array of Plan objects
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
    public function findOneBySomeField($value): ?Plan
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
