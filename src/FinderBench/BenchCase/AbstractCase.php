<?php

namespace FinderBench\BenchCase;

use Symfony\Component\Finder\Adapter\AdapterInterface;
use Symfony\Component\Finder\Finder;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
abstract class AbstractCase implements CaseInterface
{
    public function run(AdapterInterface $adapter, $root)
    {
        $time   = $this->getMicrotime();
        $finder = Finder::create()->removeAdapters()->register($adapter);

        $this->buildFinder($finder);
        foreach ($finder->in($root) as $file) {
            continue;
        }

        return $this->getMicrotime() - $time;
    }

    protected abstract function buildFinder(Finder $finder);

    private function getMicrotime()
    {
        return microtime(true) * 1000000;
    }
}
