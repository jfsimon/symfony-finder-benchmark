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

    public function run(CaseInterface $case, array $adapters)
    {
        $report = new CaseReport();

        foreach ($adapters as $adapter) {
            $report->add($adapter->getName(), $this->runOne($case, $adapter));
        }

        return $report;
    }

    private function runOne(CaseInterface $test, AdapterInterface $adapter)
    {
        $times = array();

        // file system warmup
        $test->run($adapter, $this->root);

        // profiling
        $test->run($adapter, $this->root, true);

        for ($iteration = 0; $iteration < $this->iterations; $iteration ++) {
            $times[] = $test->run($adapter, $this->root);
        }

        $count = count($times);

        return $count > 0 ? array_sum($times) / $count : null;
    }
}
