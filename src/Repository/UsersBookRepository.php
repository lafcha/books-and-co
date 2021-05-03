<?php

namespace App\Repository;

use App\Entity\UsersBook;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UsersBook|null find($id, $lockMode = null, $lockVersion = null)
 * @method UsersBook|null findOneBy(array $criteria, array $orderBy = null)
 * @method UsersBook[]    findAll()
 * @method UsersBook[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersBookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UsersBook::class);
    }

     /**
      * @return UsersBook[] Returns an array of UsersBook objects
      */
    
    public function findAllByUserId($userId)
    {
        return $this->createQueryBuilder('ub')
            ->andWhere('ub.user = :userId')
            ->setParameter('userId', $userId)
            ->leftJoin('ub.user', 'u')
            ->addSelect('u')
            ->leftJoin('ub.book', 'b')
            ->addSelect('b')
            ->getQuery()
            ->getResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?UsersBook
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
