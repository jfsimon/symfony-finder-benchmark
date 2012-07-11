<?php

namespace FinderBench;

use FinderBench\BenchCase\CaseInterface;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class CaseRunner
{
    private $iterations;
    private $adapters;

    public function __construct($iterations)
    {
        $this->iterations = $iterations;
        $this->adapters   = array();
    }

    public function registerAdapter($adapter)
    {
        if ($adapter->isSupported()) {
            $this->adapters[] = $adapter;
        }

        return $this;
    }

    public function getValidAdapters()
    {
        return $this->adapters;
    }

    public function run(CaseInterface $test, $root)
    {
        $times = array();

        foreach ($this->adapters as $adapter) {
            $times[$adapter->getName()] = array();

            for ($iteration = 0; $iteration < $this->iterations; $iteration ++) {
                $times[$adapter->getName()][] = $test->run($adapter, $root);
            }
        }

        return new Report($times);
    }
}
