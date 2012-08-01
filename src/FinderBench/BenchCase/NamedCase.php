<?php

namespace FinderBench\BenchCase;

use Symfony\Component\Finder\Finder;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class NamedCase extends AbstractCase
{
    private $name;
    private $notName;

    public function __construct(array $name, array $notName)
    {
        $this->name    = $name;
        $this->notName = $notName;
    }

    protected function buildFinder(Finder $finder)
    {
        foreach ($this->name as $name) {
            $finder->name($name);
        }

        foreach ($this->notName as $notName) {
            $finder->notName($notName);
        }
    }

    public function getName()
    {
        return 'contains:'.implode(',', $this->name).':'.implode(',', $this->notName);
    }

    public function getDescription()
    {
        $desc = 'Find files by name';

        if (count($this->name)) {
            $desc.= ' containing '.implode('|', $this->name);
        }

        if (count($this->notName)) {
            $desc.= ' not containing '.implode('|', $this->notName);
        }

        return $desc;
    }
}
