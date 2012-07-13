<?php

namespace FinderBench\Console;

use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Output\OutputInterface;
use FinderBench\CaseReport;

/**
 * @author Jean-François Simon <jeanfrancois.simon@sensiolabs.com>
 */
class ReportHelper extends Helper
{
    const CELL_WIDTH  = 15;
    const VALUE_WIDTH = 7;

    const ALIGN_LEFT  = 'left';
    const ALIGN_RIGHT = 'right';

    private $formatter;

    public function __construct(FormatterHelper $formatter)
    {
        $this->formatter = $formatter;
    }

    public function formatDetails(array $details)
    {
        $str = "\n".$this->formatter->formatBlock('Starting benchmark, it takes several minutes...', 'title', true)."\n\n";

        foreach ($details as $label => $value) {
            $str.= '<info>'
                .$this->formatter->formatCell($value, self::VALUE_WIDTH, FormatterHelper::ALIGN_RIGHT)
                ."</info> <comment>$label</comment>\n";
        }

        return $str."\n";
    }

    public function formatHeader(array $adapters)
    {
        $str = ' '.$this->formatter->formatCell('case', self::CELL_WIDTH, FormatterHelper::ALIGN_RIGHT, 'title');

        foreach ($adapters as $adapter) {
            $str.= ' '.$this->formatter->formatCell($adapter->getName(), self::CELL_WIDTH, FormatterHelper::ALIGN_RIGHT, 'title');
        }

        return $str."\n";
    }

    public function formatCase(CaseReport $report, $index)
    {
        $str = ' '.$this->formatter->formatCell($index, self::CELL_WIDTH, FormatterHelper::ALIGN_RIGHT);


        foreach ($report->getTimes() as $time) {
            $str.= ' '.$this->formatter->formatCell(null === $time ? '' : $this->formatter->formatNumber(round($time)), self::CELL_WIDTH, FormatterHelper::ALIGN_RIGHT);
        }

        return $str."\n";
    }

    public function formatCases(array $cases)
    {
        $str = "\n<comment>";

        foreach ($cases as $index => $case) {
            $str.= '<info>'.$this->formatter->formatCell($index, self::VALUE_WIDTH, FormatterHelper::ALIGN_RIGHT).'</info>';
            $str.= ' '.$case->getDescription()."\n";
        }

        return $str."</comment>\n";
    }

    public function getName()
    {
        return 'report';
    }
}
