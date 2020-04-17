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

    public function countBdCollection() {
        $query = $this->createQueryBuilder('collection')
            ->select('collection')
            ->distinct('collection');
        return $query->getQuery()->getResult();
    }

}
