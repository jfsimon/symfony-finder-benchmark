<?php

namespace FinderBench;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class Profiler
{
    private $dumpDir;

    public function __construct($dumpDir)
    {
        if (!$this->dumpDir = realpath($dumpDir)) {
            throw new \InvalidArgumentException(sprintf('Profiler dump directory "%s" does not exist.', $dumpDir));
        }
    }

    public function start()
    {
        ini_set('xhprof.output_dir', realpath(__DIR__.'/../../..'));
        //xhprof_enable(XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY);
        xhprof_enable();
    }

    public function end($name)
    {
        file_put_contents(
            $this->dumpDir.DIRECTORY_SEPARATOR.$name.'_'.str_replace(array(' ', '.'), '', microtime()),
            serialize(xhprof_disable())
        );
    }
}
