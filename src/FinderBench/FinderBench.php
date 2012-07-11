<?php

namespace FinderBench;

use FinderBench\BenchCase\CaseInterface;
use Symfony\Component\Finder\Adapter\AdapterInterface;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class FinderBench implements \Iterator
{
    private $cases;
    private $cursor;
    private $runner;
    private $files;

    public function __construct(CaseRunner $runner, FileTree $files)
    {
        $this->cases  = array();
        $this->cursor = 0;
        $this->runner = $runner;
        $this->files  = $files;
    }

    public function registerCase(CaseInterface $case)
    {
        $this->cases[] = $case;

        return $this;
    }

    public function registerAdapter(AdapterInterface $adapter)
    {
        $this->runner->registerAdapter($adapter);

        return $this;
    }

    public function getValidAdapters()
    {
        return $this->runner->getValidAdapters();
    }

    public function current()
    {
        return $this->runner->run($this->cases[$this->cursor], $this->files->getRoot());
    }

    public function next()
    {
        $this->cursor ++;
    }

    public function key()
    {
        return $this->cursor;
    }

    public function valid()
    {
        return $this->cursor < count($this->cases);
    }

    public function rewind()
    {
        $this->cursor = 0;
    }
}
