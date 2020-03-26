<?php

namespace App\Repository;

use App\Entity\BdCollection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method BdCollection|null find($id, $lockMode = null, $lockVersion = null)
 * @method BdCollection|null findOneBy(array $criteria, array $orderBy = null)
 * @method BdCollection[]    findAll()
 * @method BdCollection[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BdCollectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BdCollection::class);
    }

    // /**
    //  * @return BdCollection[] Returns an array of BdCollection objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BdCollection
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
