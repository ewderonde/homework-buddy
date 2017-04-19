<?php
/**
 * Created by PhpStorm.
 * User: Sven de Ronde
 * Date: 11-4-2017
 * Time: 12:52
 */

namespace AppBundle\Repository;


use AppBundle\Entity\Profile;
use AppBundle\Entity\User;

class ProfileRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAllUsersWithPermission(Profile $profile, User $user) {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $result = $qb->select('(uhp.id) AS uhpID', '(u.id) AS id', '(u.firstName) AS firstName', '(u.lastName) AS lastName', '(u.email) AS email', '(uhp.dateCreated) AS dateCreated    ')
            ->from('AppBundle:UserHasProfile', 'uhp')
            ->join('uhp.user', 'u')
            ->where('uhp.user != :user')
            ->setParameter('user', $user)
            ->andWhere('uhp.profile = :profile')
            ->setParameter('profile', $profile);

        return $result->getQuery()->getResult();
    }

    public function getAllUserProfiles(User $user) {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $result = $qb->select('(uhp.id) AS uhpID', '(u.email) AS email', '(uhp.dateCreated) AS dateCreated', '(p.title) AS title')
            ->from('AppBundle:UserHasProfile', 'uhp')
            ->join('uhp.user', 'u')
            ->join('uhp.profile', 'p')
            ->where('uhp.user = :user')
            ->setParameter('user', $user);

        return $result->getQuery()->getResult();
    }

    public function getCurrentUserHasProfile(User $user, Profile $profile) {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $result = $qb->select('uhp')
            ->from('AppBundle:UserHasProfile', 'uhp')
            ->join('uhp.user', 'u')
            ->where('uhp.user = :user')
            ->setParameter('user', $user)
            ->andWhere('uhp.profile = :profile')
            ->setParameter('profile', $profile);

        return $result->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }

    public function getAllActiveProfiles(User $user, $id) {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $result = $qb->select('uhp')
            ->from('AppBundle:UserHasProfile', 'uhp')
            ->where('uhp.user = :user')
            ->setParameter('user', $user);
//            ->andWhere('uhp.active = :status')
//            ->setParameter('status', 1)
//            ->andWhere('uhp.id != :id')
//            ->setParameter('id', $id);

        return $result->getQuery()->getResult();
    }

    public function findByProfileInvite($hash) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('uhp')
            ->from('AppBundle:UserHasProfile', 'uhp')
            ->where('uhp.profileInvite = :hash')
            ->setParameter('hash', $hash)
            ->andWhere('uhp.profileInvite != :nothing')
            ->setParameter('nothing', '');

        return $qb->getQuery()->setMaxResults(1)->getOneOrNullResult();
    }
}