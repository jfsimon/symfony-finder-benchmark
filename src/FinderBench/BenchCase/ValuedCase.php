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
    private $min;
    private $max;

    public function __construct($method, $min, $max)
    {
        $this->method = $method;
        $this->min    = $min;
        $this->max    = $max;
    }

    protected function buildFinder(Finder $finder)
    {
        if ($this->min === $this->max) {
            call_user_func(array($finder, $this->method), $this->min);

            return;
        }

        if (null !== $this->min) {
            call_user_func(array($finder, $this->method), '>='.$this->min);
        }

        if (null !== $this->max) {
            call_user_func(array($finder, $this->method), '<='.$this->max);
        }
    }

    public function getName()
    {
        return $this->method.':'.$this->min.'..'.$this->max;
    }

    public function getDescription()
    {
        $desc = 'Files with '.$this->method.' ';

        if ($this->min === $this->max) {
            return $desc.'of '.$this->min;
        }

        if (null === $this->min) {
            return $desc.'higher or equals to '.$this->min;
        }

        if (null === $this->max) {
            return $desc.'lower or equals to '.$this->max;
        }

        return $desc.'between '.$this->min.' and '.$this->max;
    }
}
