<?php

namespace App\Repository;

use App\Entity\PasswordRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PasswordRequest>
 *
 * @method PasswordRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method PasswordRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method PasswordRequest[]    findAll()
 * @method PasswordRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PasswordRequestRepository extends ServiceEntityRepository
{


    /**
     * @param ManagerRegistry $registry parameter
     */
    public function __construct(ManagerRegistry $registry)
    {

        parent::__construct($registry, PasswordRequest::class);

    }//end __construct()


    /**
     * @param PasswordRequest $entity parameter
     * @param bool            $flush  parameter
     *
     * @return void
     */
    public function add(PasswordRequest $entity, bool $flush = false): void
    {

        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

    }


    /**
     * @param PasswordRequest $entity parameter
     * @param bool            $flush  parameter
     *
     * @return void
     */
    public function remove(PasswordRequest $entity, bool $flush = false): void
    {

        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

    }

}
