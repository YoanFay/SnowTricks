<?php

namespace App\Repository;

use App\Entity\Tricks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tricks>
 *
 * @method Tricks|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tricks|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tricks[]    findAll()
 * @method Tricks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TricksRepository extends ServiceEntityRepository
{


    /**
     * @param ManagerRegistry $registry parameter
     */
    public function __construct(ManagerRegistry $registry)
    {

        parent::__construct($registry, Tricks::class);

    }//end __construct()


    /**
     * @param Tricks $entity parameter
     * @param bool   $flush  parameter
     *
     * @return void
     */
    public function add(Tricks $entity, bool $flush = false): void
    {

        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    /**
     * @param Tricks $entity parameter
     * @param bool   $flush  parameter
     *
     * @return void
     */
    public function remove(Tricks $entity, bool $flush = false): void
    {

        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    /**
     * @param int $start parameter
     * @param int $end   parameter
     *
     * @return Tricks[] Returns an array of Tricks objects
     */
    public function findBetweenStartAndEnd(int $start, int $end): array
    {

        return $this->createQueryBuilder('t')
            ->andWhere('t.deleted_at IS NULL')
            ->orderBy('t.id', 'ASC')
            ->setFirstResult($start)
            ->setMaxResults($end)
            ->getQuery()
            ->getResult();
    }

}
