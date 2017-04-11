<?php
/**
 * Created by PhpStorm.
 * User: Sven de Ronde
 * Date: 11-4-2017
 * Time: 17:15
 */

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;

class FilterService
{
    /**
     * @var null|\Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var Session
     */
    private $session;

    public function __construct(
        Session $session,
        RequestStack $requestStack
    )
    {
        $this->requestStack = $requestStack;
        $this->request = $this->requestStack->getCurrentRequest();
        $this->session = $session;
    }

    public function setFilters()
    {
        $view = $this->request->get('view', null);
        $day = $this->request->get('day', null);
        $week = $this->request->get('week', null);
        $month = $this->request->get('month', null);
        $year = $this->request->get('year', null);

        // Set view filter.
        if($view !== null) {
            $this->session->set('view', $view);
        }elseif ($this->session->get('view') == null) {
            $this->session->set('view', 'day');
        }

        // Set day filter
        if($day !== null) {
            $this->session->set('day', $day);
        }elseif ($this->session->get('day') == null) {
            $this->session->set('day', 'day');
        }

        // Set week filter
        if($week !== null) {
            $this->session->set('week', $week);
            $this->session->set('year', $year);
        }elseif ($this->session->get('week') == null){
            $date = new \DateTime();
            $week = $date->format("W");
            $this->session->set('week', $week);

            $date = new \DateTime();
            $this->session->set('year', $date->format('Y'));
        }

        // Set month filter WORK IN PROGRESS
        if($month !== null) {
            setlocale(LC_TIME, 'NL_nl');
            $monthName = strftime("%B");

            $date = new \DateTime();
            $monthNumber = $date->format('m');

            $this->session->set('month_number', $monthNumber);
            $this->session->set('month_name', $monthName);
        }elseif ($this->session->get('month') == null){
            setlocale(LC_TIME, 'NL_nl');
            $monthName = strftime("%B");

            $date = new \DateTime();
            $monthNumber = $date->format('m');

            $this->session->set('month_number', $monthNumber);
            $this->session->set('month_name', $monthName);
        }
    }

    public function getValues() {
        return [
            'view' => $this->session->get('view'),
            'day' => $this->session->get('day'),
            'week' => $this->session->get('week'),
            'month_name' => $this->session->get('month_name'),
            'month_number' => $this->session->get('month_number'),
            'year' => $this->session->get('year'),
        ];
    }
}