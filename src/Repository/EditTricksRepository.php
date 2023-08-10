<?php

namespace App\Repository;

use App\Entity\EditTricks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EditTricks>
 *
 * @method EditTricks|null find($id, $lockMode = null, $lockVersion = null)
 * @method EditTricks|null findOneBy(array $criteria, array $orderBy = null)
 * @method EditTricks[]    findAll()
 * @method EditTricks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EditTricksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {

        parent::__construct($registry, EditTricks::class);
    }


    public function add(EditTricks $entity, bool $flush = false): void
    {

        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function remove(EditTricks $entity, bool $flush = false): void
    {

        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    /**
     * @return EditTricks[] Returns an array of EditTricks objects
     */
    public function findLastEdit($trick): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.trick = :trick')
            ->setParameter('trick', $trick)
            ->orderBy('e.updatedAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return EditTricks[] Returns an array of EditTricks objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?EditTricks
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
