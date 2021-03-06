<?php

namespace App\Repository;

use App\Entity\Bd;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

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

    // get BD with author and category for index display
    public function findAllWithAuthorAndCategory()
    {
        $query = $this->createQueryBuilder('bd')
            ->leftJoin('bd.authors', 'a')
            ->innerJoin('bd.category', 'c')
            ->addSelect('a')
            ->addSelect('c')
            ->orderBy('bd.created_at', 'DESC')
            ->getQuery();

        return $query->execute();
    }

    // 5 last BD in db :
    public function getLastBd()
    {
        $query = $this->createQueryBuilder('bd')
            ->select('bd.filename', 'bd.created_at', 'bd.slug')
            ->setMaxResults(5)
            ->orderBy('bd.created_at', 'DESC');
        return $query->getQuery()->getResult();
    }

    // total BD :
    public function countBd()
    {
        $query = $this->createQueryBuilder('bd')
            ->select('bd');
        return $query->getQuery()->getResult();
    }

    // total BD on lend :
    public function countBdLend()
    {
        $query = $this->createQueryBuilder('bd')
            ->select('bd')
            ->where('bd.on_lend = true');
        return $query->getQuery()->getResult();
    }

    // list of all BD with cover and title
    public function findAllforListQuery(): Query
    {
        $query = $this->createQueryBuilder('bd')
            ->select('bd.title', 'bd.slug', 'bd.filename', 'bd.number')
            ->orderBy('bd.created_at', 'DESC');
        return $query->getQuery();
    }

    public function getLendBd()
    {
        $query = $this->createQueryBuilder('bd')
            ->select('bd.title', 'bd.slug', 'bd.filename')
            ->where('bd.on_lend = true')
            ->orderBy('bd.created_at', 'DESC');
        return $query->getQuery();
    }

}
