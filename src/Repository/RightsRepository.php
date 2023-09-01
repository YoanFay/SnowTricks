<?php

namespace App\Repository;

use App\Entity\Rights;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Rights>
 *
 * @method Rights|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rights|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rights[]    findAll()
 * @method Rights[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RightsRepository extends ServiceEntityRepository
{


    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rights::class);

    }//end __construct()


    /**
     * @param Rights $entity
     * @param bool   $flush
     *
     * @return void
     */
    public function add(Rights $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    /**
     * @param Rights $entity
     * @param bool   $flush
     *
     * @return void
     */
    public function remove(Rights $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
