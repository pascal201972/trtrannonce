<?php

namespace App\Repository;

use App\Entity\TrtUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method TrtUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrtUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrtUser[]    findAll()
 * @method TrtUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrtUserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrtUser::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(TrtUser $entity, bool $flush = true): void
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
    public function remove(TrtUser $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof TrtUser) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }


    public function findOneByRoleAndId($id, $role): ?TrtUser
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.id = :val')
            ->setParameter('val', $id)
            ->andWhere('t.roles = :role')
            ->setParameter('role', $role)
            ->getQuery()
            ->getOneOrNullResult();
    }
    public function findUserByRoleAndEtat($role, $etat)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.roles = :role')
            ->setParameter('role', $role)
            ->andWhere('t.valider = :etat')
            ->setParameter('etat', $etat)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return TrtUser[] Returns an array of TrtUser objects
    //  */
    /*

    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TrtUser
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
