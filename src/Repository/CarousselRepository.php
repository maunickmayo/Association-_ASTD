<?php

namespace App\Repository;

use App\Entity\Caroussel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Caroussel>
 *
 * @method Caroussel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Caroussel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Caroussel[]    findAll()
 * @method Caroussel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarousselRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Caroussel::class);
    }

    public function add(Caroussel $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Caroussel $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByTrash(string $order = 'DESC')
    {
       return $this->createQueryBuilder('a')
          ->where('a.deletedAt IS NOT NULL')
          ->orderBy('a.createdAt', $order)
          ->getQuery()
          ->getResult()
       ;
    }

//    /**
//     * @return Caroussel[] Returns an array of Caroussel objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Caroussel
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
