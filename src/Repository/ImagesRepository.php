<?php

namespace App\Repository;

use App\Entity\Images;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Images>
 *
 * @method Images|null find($id, $lockMode = null, $lockVersion = null)
 * @method Images|null findOneBy(array $criteria, array $orderBy = null)
 * @method Images[]    findAll()
 * @method Images[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImagesRepository extends ServiceEntityRepository
{


    /**
     * @param ManagerRegistry $registry parameter
     */
    public function __construct(ManagerRegistry $registry)
    {

        parent::__construct($registry, Images::class);

    }//end __construct()


    /**
     * @param Images $entity parameter
     * @param bool   $flush parameter
     *
     * @return void
     */
    public function add(Images $entity, bool $flush = false): void
    {

        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

    }


    /**
     * @param Images $entity parameter
     * @param bool   $flush parameter
     *
     * @return void
     */
    public function remove(Images $entity, bool $flush = false): void
    {

        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

    }


    /**
     * @param $tricks
     *
     * @return Images[] Returns an array of Images objects
     */
    public function countImage($tricks): array
    {

        return $this->createQueryBuilder('i')
            ->select('COUNT(i.id)')
            ->andWhere('i.tricks = :tricks')
            ->setParameter('tricks', $tricks)
            ->getQuery()
            ->getResult();
    }

}
