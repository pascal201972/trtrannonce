<?php

namespace App\Repository;

use App\Entity\TrtCandidature;
use App\Entity\TrtExperiences;
use App\Entity\TrtProfessions;
use Doctrine\ORM\ORMException;
use App\Entity\TrtProfilcandidat;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

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

    public function findProfilcandidatureByAnnonce($idannonce)
    {
        return $this->createQueryBuilder('t')
            ->from(TrtProfilcandidat::class, 'p')
            ->from(TrtProfessions::class, 'w')
            ->from(TrtExperiences::class, 'e')
            ->select('t.id,p.nom,p.prenom,t.valider, w.titre as pro, e.titre as exp ,p.cv')
            ->andWhere('t.annonce = :idann')
            ->setParameter('idann', $idannonce)
            ->andWhere('p.id = t.profil')
            ->andWhere('p.profession = w.id')
            ->andWhere('p.experience = e.id')
            ->orderBy('t.id', 'ASC')
            ->getQuery()
            ->getResult();
    }



    public function findByAnnonceAndProfil($annonce, $profil)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.annonce = :an')
            ->setParameter('an', $annonce)
            ->andWhere('t.profil = :prof')
            ->setParameter('prof', $profil)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByProfil($profil)
    {

        return $this->createQueryBuilder('t')

            ->andWhere('t.profil = :prof')
            ->setParameter('prof', $profil)
            ->getQuery()
            ->getResult();
    }
    public function findEmailUser($value)
    {
        return $this->createQueryBuilder('t')
        ->from(TrtProfilcandidat::class, 'p')
        ->from(TrtUser::class, 'u')
        ->from(TrtAnnonce::class, 'a')
        ->Where('t.annonce = a.id')
        ->andWhere('a.recruteur= p.id')
        ->andWhere('p.iduser= u.id')
    }
}
