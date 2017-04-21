<?php
/**
 * Created by PhpStorm.
 * User: Sven de Ronde
 * Date: 11-4-2017
 * Time: 12:52
 */

namespace AppBundle\Repository;


use AppBundle\Entity\Profile;
use AppBundle\Entity\Subject;

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

    public function getProfileSubjectsAsArray(Profile $profile) {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $result = $qb->select('s')
            ->from('AppBundle:Subject', 's')
            ->where('s.profile = :profile')
            ->setParameter('profile', $profile);

        return $result->getQuery()->getArrayResult();
    }

    public function getAllSubjectGrades(Profile $profile, $subject) {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $result = $qb->select('g')
            ->from('AppBundle:Grade', 'g')
            ->where('g.subject = :subject')
            ->setParameter('subject', $subject);

        return $result->getQuery()->getArrayResult();
    }

    public function getLatestGradesForProfile(Profile $profile) {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $result = $qb->select('(g.grade) AS grade', '(g.description) AS description', '(s.title) AS subject')
            ->from('AppBundle:Grade', 'g')
            ->leftJoin('g.subject', 's')
            ->where('s.profile = :profile')
            ->setParameter('profile', $profile);

        return $result->setMaxResults(8)->getQuery()->getResult();
    }

}