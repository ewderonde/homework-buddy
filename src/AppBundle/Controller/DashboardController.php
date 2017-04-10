<?php
/**
 * Created by PhpStorm.
 * User: Sven de Ronde
 * Date: 16-2-2017
 * Time: 20:22
 */

namespace AppBundle\Controller;


use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;


class DashboardController extends BaseController
{
    public function indexAction() {
        return new Response($this->templating->render('dashboard/index.html.twig'));
    }
}