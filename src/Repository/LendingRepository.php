<?php

namespace App\Repository;

use App\Entity\Lending;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Lending|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lending|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lending[]    findAll()
 * @method Lending[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LendingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lending::class);
    }

    /**
    * return all lendings with count of new messages
    */
    public function findAllByBorrowerId($borrowerId){
        return $this->createQueryBuilder('l')
                    ->leftJoin('l.linkedWith', 'lw')
                    ->leftJoin('l.usersBook', 'ub')
                    ->addSelect('ub')
                    ->leftJoin('ub.book', 'b')
                    ->addSelect('b')
                    ->leftJoin('ub.user', 'lender')
                    ->addSelect('lender')
                    ->groupBy('l.id')
                    ->addSelect('COUNT(CASE WHEN lw.isRead = 0 and lw.sender != :borrowerId THEN 0 ELSE :null end) AS nbNewMessages')
                    ->where('l.borrower = :borrowerId')
                    ->setParameter('borrowerId', $borrowerId)
                    ->setParameter('null', NULL)
                    ->getQuery()
                    ->getResult()
        ;
    }

    /**
    * return all stats of one lending
    */
    public function findAllLendingStats($lendingId){
        return $this->createQueryBuilder('l')
                    ->leftJoin('l.linkedWith', 'lw')
                    ->addSelect('lw')
                    ->leftJoin('lw.sender', 'sender')
                    ->addSelect('sender')
                    ->leftJoin('l.usersBook', 'ub')
                    ->addSelect('ub')
                    ->leftJoin('ub.book', 'b')
                    ->addSelect('b')
                    ->leftJoin('ub.user', 'lender')
                    ->addSelect('lender')
                    ->where('l.id = :lendingId')
                    ->setParameter('lendingId', $lendingId)
                    ->getQuery()
                    ->getSingleResult()
        ;
    }
    
    // /**
    //  * @return Lending[] Returns an array of Lending objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Lending
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
