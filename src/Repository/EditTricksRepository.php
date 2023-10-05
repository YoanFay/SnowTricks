<?php

namespace App\Repository;

use App\Entity\EditTricks;
use App\Entity\Tricks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
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


    /**
     * @param ManagerRegistry $registry parameter
     */
    public function __construct(ManagerRegistry $registry)
    {

        parent::__construct($registry, EditTricks::class);

    }//end __construct()


    /**
     * @param EditTricks $entity parameter
     * @param bool       $flush parameter
     *
     * @return void
     */
    public function add(EditTricks $entity, bool $flush = false): void
    {

        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

    }


    /**
     * @param EditTricks $entity parameter
     * @param bool       $flush parameter
     *
     * @return void
     */
    public function remove(EditTricks $entity, bool $flush = false): void
    {

        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    /**
     * @param Tricks $trick parameter
     *
     * @return EditTricks|null Returns an array of EditTricks objects
     */
    public function findLastEdit(Tricks $trick): ?EditTricks
    {

        try {
            return $this->createQueryBuilder('e')
                ->andWhere('e.trick = :trick')
                ->setParameter('trick', $trick)
                ->orderBy('e.updatedAt', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException|NonUniqueResultException $e) {
            return null;
        }
    }


}
