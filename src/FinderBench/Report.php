<?php

namespace FinderBench;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class Report
{
    private $times;

    public function __construct(array $times)
    {
        $this->times = $times;
    }

    public function getAverageTime($adapter)
    {
        if (!isset($this->times[$adapter])) {
            return null;
        }

        $total = 0;

        foreach ($this->times[$adapter] as $time) {
            $total += $time;
        }

        return $total / count($this->times[$adapter]);
    }
}
