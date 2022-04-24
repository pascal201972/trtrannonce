<?php

namespace App\Repository;

use App\Entity\TrtProfilrecruteur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TrtProfilrecruteur|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrtProfilrecruteur|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrtProfilrecruteur[]    findAll()
 * @method TrtProfilrecruteur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrtProfilrecruteurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrtProfilrecruteur::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(TrtProfilrecruteur $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(TrtProfilrecruteur $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return TrtProfilrecruteur[] Returns an array of TrtProfilrecruteur objects
    //  */

    public function findProfilByUser($user)
    {
        return $this->createQueryBuilder('t')
            ->from('App\Entity\TrtUser', 'u')
            ->select('u', 't')
            ->Where('t.idUser = u.id')
            ->andWhere('t.idUser = :val')
            ->setParameter('val', $user)
            ->orderBy('t.id', 'ASC')
            ->getQuery()
            ->getResult();
    }


    public function findOneByUser($user): ?TrtProfilRecruteur
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.idUser = :val')
            ->setParameter('val', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
