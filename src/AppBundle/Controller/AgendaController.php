<?php
/**
 * Created by PhpStorm.
 * User: Sven de Ronde
 * Date: 11-4-2017
 * Time: 12:04
 */

namespace AppBundle\Controller;

use Doctrine\Common\Util\Debug;
use Symfony\Component\HttpFoundation\Response;

class AgendaController extends BaseController
{
    public function indexAction(){
        // Set and retrieve session variables.
        $this->filterService->setFilters();
        $params = $this->filterService->getValues();
        $params['title'] = 'Agenda';

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
                $params['tasks'] = $taskRepository->findTasksForInterval($interval['start'], $interval['end']);
                break;
            // Tasks for whole month.
            case 3:

                break;
        }

        return new Response($this->templating->render('agenda/index.html.twig', $params ));
    }
}