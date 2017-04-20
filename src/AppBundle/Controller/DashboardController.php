<?php
/**
 * Created by PhpStorm.
 * User: Sven de Ronde
 * Date: 16-2-2017
 * Time: 20:22
 */

namespace AppBundle\Controller;


use AppBundle\Controller\BaseController;
use Doctrine\Common\Util\Debug;
use Symfony\Component\HttpFoundation\Response;


class DashboardController extends BaseController
{
    public function indexAction() {
        $taskRepo = $this->em->getRepository('AppBundle:Task');
        $date = new \DateTime();
        $data = $this->filterService->getValues();

        $data['title'] = 'Dashboard';
        $data['current_path'] = 'dashboard';
        $data['today'] = $date->format('d-m-Y');
        $data['tasks'] = $taskRepo->findTasksForDay($this->profile, $data['today'], 6);

        // Get counts of different task status.
        $complete = $taskRepo->getCompletedTaskCountForDay($this->profile, $data['today']);
        $incomplete = $taskRepo->getIncompletedTaskCountForDay($this->profile, $data['today']);
        $total = $taskRepo->getTotalTaskCountForDay($this->profile, $data['today']);

        $data['complete'] = round(($complete / $total) * 100);
        $data['incomplete'] = round(($incomplete / $total) * 100);

        $subjectRepo = $this->em->getRepository('AppBundle:Subject');
        $subjectsRaw = $subjectRepo->getProfileSubjects($this->profile);

        $data['subjects'] = [];
        foreach($subjectsRaw as $subject) {
            $data['subjects'][] = $subject->toArray();
        }

        return new Response($this->templating->render('dashboard/index.html.twig', $data));
    }
}