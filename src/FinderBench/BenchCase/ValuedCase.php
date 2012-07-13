<?php

namespace FinderBench\BenchCase;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\Iterator\SortableIterator;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class ValuedCase extends AbstractCase
{
    private $method;
    private $value;

    public function __construct($method, $value)
    {
        $this->method = $method;
        $this->value  = $value;
    }

    protected function buildFinder(Finder $finder)
    {
        call_user_func(array($finder, $this->method), $this->value);
    }

    public function getName()
    {
        return $this->method.':'.$this->value;
    }

    public function getDescription()
    {
        return 'Files with '.$this->method.' of '.$this->value;
    }
}
