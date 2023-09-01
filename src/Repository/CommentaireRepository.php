<?php

namespace App\Repository;

use App\Entity\Commentaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commentaire>
 *
 * @method Commentaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commentaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commentaire[]    findAll()
 * @method Commentaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentaireRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commentaire::class);

    }//end __construct()


    /**
     * @param Commentaire $entity
     * @param bool        $flush
     *
     * @return void
     */
    public function add(Commentaire $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    /**
     * @param Commentaire $entity
     * @param bool        $flush
     *
     * @return void
     */
    public function remove(Commentaire $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    /**
     * @param $trick
     * @param $start
     * @param $end
     *
     * @return Commentaire[] Returns an array of Tricks objects
     */
    public function findBetweenStartAndEnd($trick, $start, $end): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.tricks = :trick')
            ->setParameter('trick', $trick)
            ->orderBy('c.createdAt', 'DESC')
            ->setFirstResult($start)
            ->setMaxResults($end)
            ->getQuery()
            ->getResult()
            ;
    }
}
