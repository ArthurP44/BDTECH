<?php

namespace App\Repository;

use App\Entity\Bd;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Bd|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bd|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bd[]    findAll()
 * @method Bd[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BdRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bd::class);
    }

    public function getLastBd()
    {
        $query = $this->createQueryBuilder('bd')
            ->select('bd.filename', 'bd.created_at', 'bd.slug')
            ->setMaxResults(5)
            ->orderBy('bd.created_at', 'DESC');
        return $query->getQuery()->getResult();
    }

    public function countBd() {
        $query = $this->createQueryBuilder('bd')
            ->select('bd');
        return $query->getQuery()->getResult();
    }

    public function countBdLend() {
        $query = $this->createQueryBuilder('bd')
            ->select('bd')
            ->where('bd.on_lend = true');
        return $query->getQuery()->getResult();
    }

}
