<?php

namespace FinderBench\BenchCase;

use Symfony\Component\Finder\Finder;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class ComposedCase extends AbstractCase
{
    private $cases;

    public function __construct(array $cases)
    {
        $this->cases = $cases;
    }

    protected function buildFinder(Finder $finder)
    {
        foreach ($this->cases as $case) {
            $case->buildFinder($finder);
        }
    }

    public function getName()
    {
        return 'composed:'.implode('+', array_map(function (CaseInterface $case) {
            return $case->getName();
        }, $this->cases));
    }

    public function getDescription()
    {
        return implode(' + ', array_map(function (CaseInterface $case) {
            return $case->getDescription();
        }, $this->cases));
    }
}
