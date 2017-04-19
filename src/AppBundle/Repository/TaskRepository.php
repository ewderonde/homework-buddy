<?php
/**
 * Created by PhpStorm.
 * User: Sven
 * Date: 14-4-2017
 * Time: 19:59
 */

namespace AppBundle\Repository;


use AppBundle\Entity\Profile;

class TaskRepository extends \Doctrine\ORM\EntityRepository
{
    public function findTasksForDay(Profile $profile, $date) {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $result = $qb->select('t')
            ->from('AppBundle:Task', 't')
            ->where('t.date = :date')
            ->andWhere('t.profile = :profile')
            ->setParameter('profile', $profile)
            ->setParameter('date', new \DateTime($date))
            ->orderBy('t.timeStart', 'ASC');

        return $result->getQuery()->getResult();
    }

    public function findTasksForInterval(Profile $profile, $start, $end) {
        $start = new \DateTime($start);
        $end = new \DateTime($end);

        $qb = $this->getEntityManager()->createQueryBuilder();

        $result = $qb->select('t')
            ->from('AppBundle:Task', 't')
            ->where('t.date >= :start')
            ->setParameter('start', $start)
            ->andWhere('t.date <= :end')
            ->setParameter('end', $end)
            ->andWhere('t.profile = :profile')
            ->setParameter('profile', $profile)
            ->orderBy('t.date', 'ASC')
            ->addOrderBy('t.timeStart', 'ASC');

        return $result->getQuery()->getResult();
    }
}