<?php

namespace FinderBench\BenchCase;

use Symfony\Component\Finder\Finder;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class PathCase extends AbstractCase
{
    private $path;
    private $notPath;

    public function __construct(array $path, array $notPath)
    {
        $this->path    = $path;
        $this->notPath = $notPath;
    }

    protected function buildFinder(Finder $finder)
    {
        foreach ($this->path as $path) {
            $finder->path($path);
        }

        foreach ($this->notPath as $notPath) {
            $finder->notPath($notPath);
        }
    }

    public function getName()
    {
        return 'contains:'.implode(',', $this->path).':'.implode(',', $this->notPath);
    }

    public function getDescription()
    {
        $desc = 'Find files by path';

        if (count($this->path)) {
            $desc.= ' containing '.implode('|', $this->path);
        }

        if (count($this->notPath)) {
            $desc.= ' not containing '.implode('|', $this->notPath);
        }

        return $desc;
    }
}
