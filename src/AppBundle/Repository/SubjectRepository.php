<?php
/**
 * Created by PhpStorm.
 * User: Sven de Ronde
 * Date: 11-4-2017
 * Time: 12:52
 */

namespace AppBundle\Repository;


use AppBundle\Entity\Profile;

class SubjectRepository extends \Doctrine\ORM\EntityRepository
{
    public function getProfileSubjects(Profile $profile) {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $result = $qb->select('s')
            ->from('AppBundle:Subject', 's')
            ->where('s.profile = :profile')
            ->setParameter('profile', $profile);

        return $result->getQuery()->getResult();
    }
}