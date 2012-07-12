<?php

namespace FinderBench\Console;

use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Output\OutputInterface;
use FinderBench\Report;
use FinderBench\FinderBench;

/**
 * @author Jean-François Simon <jeanfrancois.simon@sensiolabs.com>
 */
class ReportFormatterHelper extends Helper
{
    const TIME_WIDTH        = 10;
    const MAX_HEADING_WIDTH = 50;

    private $width;

    public function __construct($width)
    {
        $this->width = $width;
    }

    public function formatDetails(OutputInterface $output, FinderBench $bench)
    {
        return $this;
    }

    public function formatHeader(OutputInterface $output, array $adapters)
    {
        $output->write($this->formatString('Bench case', $this->computeHeadingWidth(count($adapters))));

        foreach ($adapters as $adapter) {
            $output->write(' '.$this->formatString($adapter->getName(), self::TIME_WIDTH));
        }

        $output->write("\n\n");

        return $this;
    }

    public function formatReport(OutputInterface $output, Report $report, array $cases, array $adapters)
    {
        foreach ($cases as $case) {
            $output->write($this->formatString($case->getDescription(), $this->computeHeadingWidth(count($adapters))));

            foreach ($adapters as $adapter) {
                $output->write($this->formatTime($report, $case->getName(), $adapter->getName()));
            }

            $output->write("\n");
        }

        return $this;
    }

    public function getName()
    {
        return 'report';
    }

    private function formatString($string, $width)
    {
        $length = strlen($string);

        if ($length > $width) {
            return substr($string, $width);
        }

        if ($length < $width) {
            return $string.str_repeat(' ', $width - $length);
        }

        return $string;
    }

    private function formatTime(Report $report, $case, $adapter)
    {
        $time = $report->computeTime($case, $adapter);
        $str  = null === $time ? '' : (string) round($time);

        return $this->formatString(' '.$str, self::TIME_WIDTH);
    }

    private function computeHeadingWidth($adaptersCount)
    {
        $width = $this->width - $adaptersCount * (self::TIME_WIDTH + 1);

        return min($width, self::MAX_HEADING_WIDTH);
    }
}
