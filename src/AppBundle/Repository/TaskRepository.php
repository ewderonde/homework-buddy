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
    public function findTasksForDay($date) {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $result = $qb->select('t')
            ->from('AppBundle:Task', 't')
            ->where('t.date = :date')
            ->setParameter('date', new \DateTime($date))
            ->orderBy('t.timeStart', 'ASC');

        return $result->getQuery()->getResult();
    }

    public function findTasksForInterval($start, $end) {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $result = $qb->select('t')
            ->from('AppBundle:Task', 't')
            ->where('t.date = :date')
            ->setParameter('date', new \DateTime())
            ->orderBy('t.timeStart', 'ASC');

        return $result->getQuery()->getResult();
    }

    public function findTasksForMonth($date) {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $result = $qb->select('t')
            ->from('AppBundle:Task', 't')
            ->where('t.date = :date')
            ->setParameter('date', new \DateTime($date))
            ->orderBy('t.timeStart', 'ASC');

        return $result->getQuery()->getResult();
    }

}