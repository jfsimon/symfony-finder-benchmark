<?php

namespace FinderBench\BenchCase;

use Symfony\Component\Finder\Finder;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class NameContainsCase extends AbstractCase
{
    private $contains;
    private $notContains;

    public function __construct(array $contains, array $notContains)
    {
        $this->contains    = $contains;
        $this->notContains = $notContains;
    }

    protected function buildFinder(Finder $finder)
    {
        foreach ($this->contains as $contains) {
            $finder->contains($contains);
        }

        foreach ($this->notContains as $notContains) {
            $finder->notContains($notContains);
        }
    }

    public function getName()
    {
        return 'contains:'.implode(',', $this->contains).':'.implode(',', $this->notContains);
    }

    public function getDescription()
    {
        $desc = 'Find files by name';

        if (count($this->contains)) {
            $desc.= ' containing '.implode('|', $this->contains);
        }

        if (count($this->notContains)) {
            $desc.= ' not containing '.implode('|', $this->notContains);
        }

        return $desc;
    }
}
