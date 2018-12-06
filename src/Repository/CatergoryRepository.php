<?php

namespace App\Repository;

use App\Entity\Catergory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Catergory|null find($id, $lockMode = null, $lockVersion = null)
 * @method Catergory|null findOneBy(array $criteria, array $orderBy = null)
 * @method Catergory[]    findAll()
 * @method Catergory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CatergoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Catergory::class);
    }

//    /**
//     * @return Catergory[] Returns an array of Catergory objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Catergory
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
