<?php

namespace FinderBench\BenchCase;

use FinderBench\Profiler;
use Symfony\Component\Finder\Adapter\AdapterInterface;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
interface CaseInterface
{
    function profile(Profiler $profiler);
    function getName();
    function getDescription();
    function run(AdapterInterface $adapter, $root, $profile = false);
}
