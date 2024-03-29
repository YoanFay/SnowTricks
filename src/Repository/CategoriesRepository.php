<?php

namespace App\Repository;

use App\Entity\Categories;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Categories>
 *
 * @method Categories|null find($id, $lockMode = null, $lockVersion = null)
 * @method Categories|null findOneBy(array $criteria, array $orderBy = null)
 * @method Categories[]    findAll()
 * @method Categories[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoriesRepository extends ServiceEntityRepository
{


    /**
     * @param ManagerRegistry $registry parameter
     */
    public function __construct(ManagerRegistry $registry)
    {

        parent::__construct($registry, Categories::class);

    }//end __construct()


    /**
     * @param Categories $entity parameter
     * @param bool       $flush  parameter
     *
     * @return void
     */
    public function add(Categories $entity, bool $flush = false): void
    {

        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

    }


    /**
     * @param Categories $entity parameter
     * @param bool       $flush  parameter
     *
     * @return void
     */
    public function remove(Categories $entity, bool $flush = false): void
    {

        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

    }


}
