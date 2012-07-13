<?php

namespace FinderBench\BenchCase;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\Iterator\SortableIterator;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class SortedFilesCase extends AbstractCase
{
    const BY_NAME     = 1;
    const BY_TYPE     = 2;
    const BY_ACCESSED = 4;
    const BY_CHANGED  = 5;
    const BY_MODIFIED = 6;

    private $by;
    private $sorts;

    public function __construct($by)
    {
        $this->by    = $by;
        $this->sorts = array(
            self::BY_NAME     => array('name' => 'name',          'method' => 'sortByName'),
            self::BY_TYPE     => array('name' => 'type',          'method' => 'sortByType'),
            self::BY_ACCESSED => array('name' => 'accessed time', 'method' => 'sortByAccessedTime'),
            self::BY_CHANGED  => array('name' => 'changed time',  'method' => 'sortByChangedTime'),
            self::BY_MODIFIED => array('name' => 'modified time', 'method' => 'sortByModifiedTime'),
        );
    }

    protected function buildFinder(Finder $finder)
    {
        call_user_func(array($finder, $this->sorts[$this->by]['method']));
    }

    public function getName()
    {
        return 'sort:'.$this->by;
    }

    public function getDescription()
    {
        return 'Sort files by '.$this->sorts[$this->by]['name'];
    }
}
