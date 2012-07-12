<?php

namespace FinderBench;

use FinderBench\BenchCase\CaseInterface;
use Symfony\Component\Finder\Adapter\AdapterInterface;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class CaseRunner
{
    private $iterations;
    private $root;
    private $adapters;

    public function __construct($iterations, $root)
    {
        $this->iterations = $iterations;
        $this->root       = $root;
        $this->adapters   = array();
    }

    public function run(CaseInterface $test, AdapterInterface $adapter)
    {
        $times = array();

        for ($iteration = 0; $iteration < $this->iterations; $iteration ++) {
            $times[$adapter->getName()][] = $test->run($adapter, $this->root);
        }

        return count($times) > 0 ? array_sum($times) / count($times) : null;
    }
}
