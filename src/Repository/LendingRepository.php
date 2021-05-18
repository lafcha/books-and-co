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
    * return all lendings with count of new messages by borrowerId
    */
    public function findAllByBorrowerId($borrowerId, $page, $limit, $statusFilter){
        $qb = $this->createQueryBuilder('l')
            ->leftJoin('l.linkedWith', 'lw')
            ->leftJoin('l.usersBook', 'ub')
            ->addSelect('ub')
            ->leftJoin('ub.book', 'b')
            ->addSelect('b')
            ->leftJoin('ub.user', 'lender')
            ->addSelect('lender')
            ->groupBy('l.id')
            ->orderBy('max(lw.createdAt)', 'DESC')
            ->addSelect('COUNT(CASE WHEN lw.isRead = 0 and lw.sender != :borrowerId THEN 0 ELSE :null end) AS nbNewMessages')
            ->where('l.borrower = :borrowerId')
            ->setParameter('borrowerId', $borrowerId)
            ->setParameter('null', NULL)
            ->setFirstResult(($page * $limit) -$limit)
            ->setMaxResults($limit)
            ;
        if ($statusFilter !== null) {
            $qb->andWhere('l.status = :statusFilter')
            ->setParameter('statusFilter', $statusFilter)
            ;
        }
        return $qb->getQuery()
            ->getResult()
        ;
    }
    /**
    * return all lendings with count of new messages by borrowerId
    */
    public function findByUsersBookIdAndUserHasNoBorrowingOnIt($usersBookId, $borrowerId){
        return $this->createQueryBuilder('l')
            ->leftJoin('l.linkedWith', 'lw')
            ->leftJoin('l.usersBook', 'ub')
            ->addSelect('ub')
            ->andWhere('l.borrower = :borrowerId')
            ->andWhere('ub.id = :usersBookId')
            ->andWhere('l.status != 2')
            ->setParameter('borrowerId', $borrowerId)
            ->setParameter('usersBookId', $usersBookId)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * return all lendings with count of new messages by lenderId
    */
    public function findAllByLenderId($lenderId, $page, $limit, $statusFilter){
        $qb =  $this->createQueryBuilder('l')
            ->leftJoin('l.linkedWith', 'lw')
            ->leftJoin('l.borrower', 'borrower')
            ->addSelect('borrower')
            ->leftJoin('l.usersBook', 'ub')
            ->addSelect('ub')
            ->leftJoin('ub.book', 'b')
            ->addSelect('b')
            ->addSelect('lw')
            ->orderBy('max(lw.createdAt)', 'DESC')
            ->addSelect('COUNT(CASE WHEN lw.isRead = 0 and lw.sender != :lenderId THEN 0 ELSE :null end) AS nbNewMessages')
            ->where('ub.user = :lenderId')
            ->setParameter('lenderId', $lenderId)
            ->setParameter('null', NULL)
            ->setFirstResult(($page * $limit) -$limit)
            ->setMaxResults($limit)
        ;
        if ($statusFilter !== null) {
            $qb->andWhere('l.status = :statusFilter')
            ->setParameter('statusFilter', $statusFilter)
            ;
        }
        return $qb->groupBy('l.id')
            ->distinct()
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

    /**
     * Returns number of lending
     * @return int 
     */
    public function getLendingCountByBorrowerId($borrowerId, $statusFilter){
        
        $qb = $this->createQueryBuilder('l')
            ->select('COUNT(l)')
            ->leftJoin('l.borrower', 'ub')
            ->andWhere('l.borrower = :borrowerId')
            ->setParameter('borrowerId', $borrowerId)
        ;

        if ($statusFilter) {
            $qb->andWhere('l.status = :statusFilter')
            ->setParameter('statusFilter', $statusFilter)
            ;
        }
        return $qb->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * Returns number of lending
     * @return int 
     */
    public function getLendingCountByLenderId($lenderId, $statusFilter){
        
        $qb =  $this->createQueryBuilder('l')
            ->select('COUNT(l)')
            ->leftJoin('l.usersBook', 'ub')
            ->andWhere('ub.user = :lenderId')
            ->setParameter('lenderId', $lenderId)
            ;
            if ($statusFilter) {
                $qb->andWhere('l.status = :statusFilter')
                ->setParameter('statusFilter', $statusFilter)
                ;
            }
            return $qb->getQuery()
                ->getSingleScalarResult()
            ;
        ;
    }

    /**
    * return all lendings with count of new messages by lenderId
    */
    public function findNotificationNumber($lenderId, $userType){
        $qb = $this->createQueryBuilder('l')
            ->leftJoin('l.linkedWith', 'lw')
            ->leftJoin('l.usersBook', 'ub')
            ->addSelect('COUNT(CASE WHEN lw.isRead = 0 and lw.sender != :lenderId THEN 0 ELSE :null end) AS nbNewMessages')
            ->setParameter('lenderId', $lenderId)
            ->setParameter('null', NULL)
        ;
        if ($userType === 'borrower') {
            $qb->where('l.borrower = :lenderId');
        } elseif ($userType === 'lender') {
            $qb->where('ub.user = :lenderId');
        }
        return $qb->groupBy('l.id')
            ->getQuery()
            ->getResult()
        ;
    }
}
