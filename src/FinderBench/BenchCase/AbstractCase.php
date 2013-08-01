<?php

namespace FinderBench\BenchCase;

use FinderBench\Profiler;
use Symfony\Component\Finder\Adapter\AdapterInterface;
use Symfony\Component\Finder\Finder;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
abstract class AbstractCase implements CaseInterface
{
    private $profiler;

    public function profile(Profiler $profiler)
    {
        $this->profiler = $profiler;
    }

    public function run(AdapterInterface $adapter, $root, $profile = false)
    {
        $time   = $this->getMicrotime();
        $finder = Finder::create()->removeAdapters()->addAdapter($adapter);

        $this->buildFinder($finder);

        if ($profile) {
            $this->profiler->start();
        }

        foreach ($finder->in($root) as $file) {
            continue;
        }

        if ($profile) {
            $this->profiler->end($adapter->getName().'_'.$this->getName());
        }

        return $this->getMicrotime() - $time;
    }

    protected abstract function buildFinder(Finder $finder);

    private function getMicrotime()
    {
        return microtime(true) * 1000000;
    }
}
