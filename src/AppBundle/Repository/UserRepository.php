<?php
/**
 * Created by PhpStorm.
 * User: Sven
 * Date: 14-4-2017
 * Time: 19:59
 */

namespace AppBundle\Repository;


use AppBundle\Entity\Profile;
use AppBundle\Entity\User;

class UserRepository extends \Doctrine\ORM\EntityRepository
{
    public function findUserByEmail($email) {
       $qb = $this->getEntityManager()->createQueryBuilder();

       $qb->select('u')
           ->from('AppBundle:User', 'u')
           ->where('u.email = :email')
           ->setParameter('email', $email);

       return $qb->setMaxResults(1)
           ->getQuery()
           ->getOneOrNullResult();
    }

    public function getActiveProfile(User $user) {
       $qb = $this->getEntityManager()->createQueryBuilder();

       $qb->select('p.id')
           ->from('AppBundle:UserHasProfile', 'uhp')
           ->join('uhp.profile', 'p')
           ->where('uhp.user = :user')
           ->setParameter('user', $user)
           ->andWhere('uhp.active = :active')
           ->setParameter('active', 1);

       return $qb->setMaxResults(1)
           ->getQuery()
           ->getOneOrNullResult();
    }

    public function checkForDuplicateInvite(User $user, Profile $profile) {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('COUNT(uhp.id)')
            ->from('AppBundle:UserHasProfile', 'uhp')
            ->join('uhp.profile', 'p')
            ->where('uhp.user = :user')
            ->setParameter('user', $user)
            ->andWhere('uhp.profile = :profile')
            ->setParameter('profile', $profile);

        return $qb->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult();
    }
}