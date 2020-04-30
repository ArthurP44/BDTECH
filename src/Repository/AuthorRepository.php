<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    // get all distinct authors in DB
    public function countAuthor()
    {
        $query = $this->createQueryBuilder('author')
            ->select('author')
            ->distinct('author')
            ->orderBy('author.name', 'ASC');
        return $query->getQuery()->getResult();
    }

    // get all authors with bd count for author admin index
    public function findAllWithBd()
    {
        $query = $this->createQueryBuilder('a')
            ->leftJoin('a.bds', 'bd')
            ->addSelect('bd')
            ->orderBy('a.name', 'ASC')
            ->getQuery();

        return $query->execute();
    }

    // get all BD list by author for list
    public function findAllBdByAuthorQuery($author)
    {
        $query = $this->createQueryBuilder('a')
            ->leftJoin('a.bds', 'bd')
            ->select('a.name', 'bd.title', 'bd.slug', 'bd.filename')
            ->where('a.name = :author')
            ->setParameter('author', $author);
        /*->orderBy('bd.created_at', 'DESC')*/

        return $query->getQuery();
    }

}
