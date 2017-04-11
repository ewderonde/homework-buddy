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

        return new Response($this->templating->render('agenda/index.html.twig', $params ));
    }
}