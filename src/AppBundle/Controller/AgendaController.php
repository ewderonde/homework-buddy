<?php
/**
 * Created by PhpStorm.
 * User: Sven de Ronde
 * Date: 11-4-2017
 * Time: 12:04
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use Doctrine\Common\Util\Debug;
use Symfony\Bundle\FrameworkBundle\Routing\RedirectableUrlMatcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Intl\Tests\Data\Provider\Json\JsonRegionDataProviderTest;

class AgendaController extends BaseController
{
    public function indexAction(){
        // Set and retrieve session variables.
        $this->filterService->setFilters();
        $params = $this->filterService->getValues();
        $params['title'] = 'Agenda';
        $params['current_path'] = 'agenda';

        $taskRepository = $this->em->getRepository('AppBundle:Task');

        $params['tasks'] = [];
        switch ($params['view']) {
            // Single day.
            case 1:
                $params['tasks'] = $taskRepository->findTasksForDay($params['day']);
                break;
            // Tasks for week.
            case 2:
                $interval = $this->agendaService->getWeekInterval($params['week'], $params['year']);
                $tasksRaw = $taskRepository->findTasksForInterval($interval['start'], $interval['end']);
                $params['tasks'] = $this->transformTaskData($interval['start'], $interval['end'], $tasksRaw);
                break;
            // Tasks for whole month.
            case 3:
                $interval = $this->agendaService->getMonthInterval($params['month_number'], $params['year']);
                $tasksRaw = $taskRepository->findTasksForInterval($interval['start'], $interval['end']);
                $params['tasks'] = $this->transformTaskData($interval['start'], $interval['end'], $tasksRaw);
                break;
        }

        return new Response($this->templating->render('agenda/index.html.twig', $params ));
    }

    private function transformTaskData($start, $end, $tasks) {
        // Start date
        $date = $start;

        $tasksGroupedByDay = [];
        $days = [];

        // Add first day to array;
        $tasksGroupedByDay[$date] = ['date' => $date, 'tasks' => [] ];

        while (strtotime($date) < strtotime($end)) {
            $date = date ("d-m-Y", strtotime("+1 day", strtotime($date)));
            $tasksGroupedByDay[$date] = ['date' => $date, 'tasks' => [] ];
        }

        /** @var Task $task */
        foreach ($tasks as $task) {
            $index = $task->getDate()->format('d-m-Y');
            $tasksGroupedByDay[$index]['tasks'][] = $task;
        }

        return $tasksGroupedByDay;
    }

    public function changeTaskSatusAction (Task $task) {
        $currentStatus = $task->isStatus();
        $newStatus = ($currentStatus == 1)? 0 : 1;

        $task->setStatus($newStatus);
        $this->em->flush();

        return new RedirectResponse($this->router->generate('agenda_index'));

    }
}