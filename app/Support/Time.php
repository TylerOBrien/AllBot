<?php

namespace App\Support;

use DateInterval;

class Time
{
    /**
     * @param  string  $period  The time period to convert to milliseconds.
     *
     * @return int
     */
    static public function getMilliseconds(string $period): int
    {
        $pivot = strpos($period, '.');

        if ($pivot === false) {
            $milliseconds = 0;
        } else {
            $end = strpos($period, 'S');
            $milliseconds = substr($period, $pivot + 1, min(3, $end - $pivot));
            $period = substr($period, 0, $pivot) . 'S';
        }

        $interval = new DateInterval($period);

        return ($interval->h * 3600000) + ($interval->i * 60000) + ($interval->s * 1000) + intval($milliseconds);

        /* $period = str_replace('PT', '', $period);
        $pivot = strpos($period, 'H');

        if ($pivot === false) {
            $hours = 0;
        } else {
            $hours = substr($period, 0, $pivot);
        }

        $pivot = strpos($period, 'M', $pivot);

        if ($pivot === false) {
            $minutes = 0;
        } else {
            $minutes = substr($period, 0, $pivot);
        }

        $msPivot = strpos($period, '.', $pivot);

        if ($msPivot === false) {
            $milliseconds = 0;
        } else {
            $milliseconds = substr($period, $msPivot + 1, 3);
        }

        return (intval($hours) * 24 * 60000) + (intval($minutes) * 60000) + (intval($seconds) * 1000) + intval($milliseconds); */
    }
}
