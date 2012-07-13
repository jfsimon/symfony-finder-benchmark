<?php

namespace FinderBench;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class CaseReport
{
    private $times;

    public function __construct()
    {
        $this->times = array();
    }

    public function add($adapter, $time)
    {
        if (!isset($this->times[$adapter])) {
            $this->times[$adapter] = array();
        }

        $this->times[$adapter][] = $time;
    }

    public function getTimes()
    {
        $averages = array();

        foreach ($this->times as $adapter => $times) {
            $count = count($this->times[$adapter]);
            $averages[] = 0 === $count ? null : array_sum($this->times[$adapter]) / $count;
        }

        return $averages;
    }
}
