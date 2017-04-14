<?php

/**
 * Created by PhpStorm.
 * User: Sven de Ronde
 * Date: 11-4-2017
 * Time: 12:45
 */
namespace AppBundle\Service;

use Doctrine\Common\Util\Debug;

class AgendaService
{
    public function getWeekInterval($weekNumber, $year) {
        $week = $weekNumber;

        $timestamp = mktime( 0, 0, 0, 1, 1,  $year ) + ( $week * 7 * 24 * 60 * 60 );
        $timestamp_for_monday = $timestamp - 86400 * ( date( 'N', $timestamp ) - 1 );
        $timestamp_for_sunday = $timestamp - 86400 * ( date( 'N', $timestamp ) - 7 );

        $date_for_monday = date( 'd-m-Y', $timestamp_for_monday );
        $date_for_sunday = date( 'd-m-Y', $timestamp_for_sunday );

        return ['start' => $date_for_monday, 'end' => $date_for_sunday];
    }

    public function getMonthInterval($monthNumber, $year) {
        $month = $monthNumber+1;

        $query_date = '1-'.$month."-".$year;

        // First day of the month.
        $first = date('01-m-Y', strtotime($query_date));

        // Last day of the month.
        $last = date('t-m-Y', strtotime($query_date));

        return ['start' => $first, 'end' => $last];
    }
}