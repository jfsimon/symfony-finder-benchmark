<?php

namespace FinderBench\BenchCase;

use Symfony\Component\Finder\Adapter\AdapterInterface;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
interface CaseInterface
{
    function getName();
    function getDescription();
    function run(AdapterInterface $adapter, $root);
}
