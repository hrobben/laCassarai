<?php

namespace App\Repository;

use App\Entity\BankCart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method BankCart|null find($id, $lockMode = null, $lockVersion = null)
 * @method BankCart|null findOneBy(array $criteria, array $orderBy = null)
 * @method BankCart[]    findAll()
 * @method BankCart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BankCartRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BankCart::class);
    }

//    /**
//     * @return BankCart[] Returns an array of BankCart objects
//     */
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
    public function findOneBySomeField($value): ?BankCart
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
