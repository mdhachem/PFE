<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Event;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Entity\SearchEntity\EventSearch;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{

    private $repo;

    public function __construct(RegistryInterface $registry, PlanRepository $repo)
    {
        parent::__construct($registry, Event::class);
        $this->repo = $repo;
    }

    public function countEventByUser(User $user)
    {

        return $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->where('p.plan IN (:idplan) ')
            ->setParameter('idplan', $this->repo->userPlanId($user))
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findEventByUser(User $user, EventSearch $search)
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

    public function EventByUserByPlan(User $user)
    {

        return $this->createQueryBuilder('p')
            ->where('p.plan IN (:idplan) ')
            ->setParameter('idplan', $this->repo->userPlanId($user))
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Event[] Returns an array of Event objects
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
    public function findOneBySomeField($value): ?Event
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
