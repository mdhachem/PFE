<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Entity\User;
use App\Entity\SearchEntity\ProductSearch;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{

    private $repo;

    public function __construct(RegistryInterface $registry, PlanRepository $repo)
    {
        parent::__construct($registry, Product::class);
        $this->repo = $repo;
    }

    public function findProductByUser(User $user, ProductSearch $search)
    {
        $query = $this->createQueryBuilder('p')
            ->where('p.plan IN (:idplan) ')
            ->setParameter('idplan', $this->repo->userPlanId($user));

        if ($search->getName()) {
            $query = $query->andWhere('p.name like :name')
                ->setParameter('name', '%' . $search->getName() . '%');
        }

        return $query->getQuery();
    }

    public function countProductByUser(User $user)
    {

        return $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->where('p.plan IN (:idplan) ')
            ->setParameter('idplan', $this->repo->userPlanId($user))
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function ProductByUserByPlan(User $user)
    {

        return $this->createQueryBuilder('p')
            ->where('p.plan IN (:idplan) ')
            ->setParameter('idplan', $this->repo->userPlanId($user))
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
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
    public function findOneBySomeField($value): ?Product
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
