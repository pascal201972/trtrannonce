<?php

namespace App\Repository;

use App\Entity\TrtCandidature;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TrtCandidature|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrtCandidature|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrtCandidature[]    findAll()
 * @method TrtCandidature[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrtCandidatureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrtCandidature::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(TrtCandidature $entity, bool $flush = true): void
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
    public function remove(TrtCandidature $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return TrtCandidature[] Returns an array of TrtCandidature objects
    //  */

    public function findByAnnonceAndProfil($idannonce, $idprofil)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.annonce= :idann')
            ->setParameter('idann', $idannonce)
            ->andWhere('t.profil= :idprofil')
            ->setParameter('idprofil', $idprofil)
            ->orderBy('t.id', 'ASC')
            ->getQuery()
            ->getResult();
    }


    /*
    public function findOneBySomeField($value): ?TrtCandidature
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
