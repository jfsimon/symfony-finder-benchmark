<?php

namespace FinderBench;

use FinderBench\BenchCase\CaseInterface;
use Symfony\Component\Finder\Adapter\AdapterInterface;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class FinderBench
{
    private $cases;
    private $adapters;
    private $files;
    private $runner;

    public function __construct(FilesTree $files, CaseRunner $runner)
    {
        $this->cases    = array();
        $this->adapters = array();
        $this->files    = $files;
        $this->runner   = $runner;
    }

    public function registerCase(CaseInterface $case)
    {
        $this->cases[] = $case;

        return $this;
    }

    public function registerAdapter(AdapterInterface $adapter)
    {
        if ($adapter->isSupported()) {
            $this->adapters[] = $adapter;
        }

        return $this;
    }

    public function buildReport()
    {
        $this->files->build();

        $report = new Report();

        foreach ($this->cases as $case) {
            foreach ($this->adapters as $adapter) {
                $time = $this->runner->run($case, $adapter);
                $report->add($case->getName(), $adapter->getName(), $time);
            }
        }

        $this->files->remove();

        return $report;
    }

    public function getFiles()
    {
        return $this->files;
    }

    public function getAdapters()
    {
        return $this->adapters;
    }

    public function getCases()
    {
        return $this->cases;
    }
}
