<?php

namespace FinderBench;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class Report
{
    private $cases;

    public function add($case, $adapter, $time)
    {
        if (!isset($this->cases[$case])) {
            $this->cases[$case] = array();
        }

        if (!isset($this->cases[$case][$adapter])) {
            $this->cases[$case][$adapter] = array();
        }

        $this->cases[$case][$adapter][] = $time;
    }

    public function computeTime($case, $adapter)
    {
        if (!isset($this->cases[$case][$adapter])) {
            return null;
        }

        $count = count($this->cases[$case][$adapter]);

        return 0 === $count ? null : array_sum($this->cases[$case][$adapter]) / $count;
    }
}
