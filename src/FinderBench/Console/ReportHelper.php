<?php

namespace FinderBench\Console;

use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Output\OutputInterface;
use FinderBench\Report;
use FinderBench\FinderBench;

/**
 * @author Jean-François Simon <jeanfrancois.simon@sensiolabs.com>
 */
class ReportHelper extends Helper
{
    const CELL_WIDTH = 10;

    const ALIGN_LEFT  = 'left';
    const ALIGN_RIGHT = 'right';

    private $formatter;
    private $width;
    private $buffer;

    public function __construct(FormatterHelper $formatter, $width)
    {
        $this->formatter = $formatter;
        $this->width     = $width;
        $this->buffer    = '';
    }

    public function addDetails(FinderBench $bench)
    {
        $this->buffer.= "\n";
        $this->buffer.= $this->formatter->formatBlock('Starting benchmark, it could take a while...', 'title', true)."\n";
        $this->buffer.= "\n<comment>";
        $this->buffer.= $this->formatter->formatCell($bench->getFiles()->getFilesCount(), self::CELL_WIDTH, FormatterHelper::ALIGN_RIGHT)." files\n";
        $this->buffer.= $this->formatter->formatCell($bench->getFiles()->getDirsCount(), self::CELL_WIDTH, FormatterHelper::ALIGN_RIGHT)." directories\n";
        $this->buffer.= $this->formatter->formatCell(count($bench->getCases()), self::CELL_WIDTH, FormatterHelper::ALIGN_RIGHT)." bench cases\n";
        $this->buffer.= $this->formatter->formatCell(count($bench->getAdapters()), self::CELL_WIDTH, FormatterHelper::ALIGN_RIGHT)." supported adapters\n";
        $this->buffer.= "</comment>\n";

        return $this;
    }

    public function addHeader(array $adapters)
    {
        $this->buffer.= ' '.$this->formatter->formatCell('case', self::CELL_WIDTH, FormatterHelper::ALIGN_LEFT, 'title');

        foreach ($adapters as $adapter) {
            $this->buffer.= ' '.$this->formatter->formatCell($adapter->getName(), self::CELL_WIDTH, FormatterHelper::ALIGN_RIGHT, 'title');
        }

        $this->buffer.= "\n";

        return $this;
    }

    public function addReport(Report $report, array $cases, array $adapters)
    {
        foreach ($cases as $index => $case) {
            $this->buffer.= ' '.$this->formatter->formatCell($index, self::CELL_WIDTH);

            foreach ($adapters as $adapter) {
                $this->addTime($report, $case->getName(), $adapter->getName());
            }

            $this->buffer.= "\n\n";
        }

        return $this;
    }

    public function addCases(array $cases)
    {
        $this->buffer.= '<comment>';

        foreach ($cases as $index => $case) {
            $this->buffer.= $this->formatter->formatCell($index, self::CELL_WIDTH, FormatterHelper::ALIGN_RIGHT);
            $this->buffer.= ' '.$case->getDescription()."\n";
        }

        $this->buffer.= "</comment>\n";

        return $this;
    }

    public function write(OutputInterface $output)
    {
        $output->write($this->buffer);
        $this->buffer = '';

        return $this;
    }

    public function getName()
    {
        return 'report';
    }

    private function addTime(Report $report, $case, $adapter)
    {
        $time = $report->computeTime($case, $adapter);
        $str  = null === $time ? '' : (string) round($time);

        $this->buffer.= ' '.$this->formatter->formatCell($str, self::CELL_WIDTH, FormatterHelper::ALIGN_RIGHT);
    }
}
