<?php

namespace App\Repository;

use App\Entity\Video;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Video>
 *
 * @method Video|null find($id, $lockMode = null, $lockVersion = null)
 * @method Video|null findOneBy(array $criteria, array $orderBy = null)
 * @method Video[]    findAll()
 * @method Video[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoRepository extends ServiceEntityRepository
{


    /**
     * @param ManagerRegistry $registry parameter
     */
    public function __construct(ManagerRegistry $registry)
    {

        parent::__construct($registry, Video::class);

    }//end __construct()


    /**
     * @param Video $entity parameter
     * @param bool  $flush parameter
     *
     * @return void
     */
    public function add(Video $entity, bool $flush = false): void
    {

        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

    }


    /**
     * @param Video $entity parameter
     * @param bool  $flush parameter
     *
     * @return void
     */
    public function remove(Video $entity, bool $flush = false): void
    {

        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


}
