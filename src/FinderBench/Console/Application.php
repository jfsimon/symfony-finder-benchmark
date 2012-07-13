<?php

namespace FinderBench\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Finder\Adapter;
use FinderBench\Command;
use FinderBench\BenchCase;

/**
 * @author Jean-François Simon <jeanfrancois.simon@sensiolabs.com>
 */
class Application extends BaseApplication
{
    public function __construct()
    {
        parent::__construct('FinderBench', 'Beta');
    }

    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        if (null === $output) {
            $output = new ConsoleOutput(ConsoleOutput::VERBOSITY_NORMAL, null, new OutputFormatter(null, array(
                'fastest' => new OutputFormatterStyle('white', 'green'),
                'average' => new OutputFormatterStyle('white', 'black'),
                'slowest' => new OutputFormatterStyle('white', 'red'),
                'title'   => new OutputFormatterStyle('white', 'blue'),
            )));
        }

        return parent::run($input, $output);
    }

    public function getCases()
    {
        return array(
            new BenchCase\NameContainsCase(array('a*'), array()),
            new BenchCase\ComposedCase(array(
                new BenchCase\NameContainsCase(array('a*'), array()),
                new BenchCase\NameContainsCase(array('a*'), array('*b')),
            )),
            new BenchCase\SortedFilesCase(BenchCase\SortedFilesCase::BY_NAME),
            new BenchCase\SortedFilesCase(BenchCase\SortedFilesCase::BY_MODIFIED),
            new BenchCase\ComposedCase(array(
                new BenchCase\NameContainsCase(array('a*'), array()),
                new BenchCase\SortedFilesCase(BenchCase\SortedFilesCase::BY_NAME),
            )),
            new BenchCase\ValuedCase('depth', 2),
            new BenchCase\ComposedCase(array(
                new BenchCase\NameContainsCase(array('a*'), array()),
                new BenchCase\SortedFilesCase(BenchCase\SortedFilesCase::BY_NAME),
                new BenchCase\ValuedCase('depth', 2),
            )),
            new BenchCase\ValuedCase('size', 2),
            new BenchCase\ComposedCase(array(
                new BenchCase\NameContainsCase(array('a*'), array()),
                new BenchCase\SortedFilesCase(BenchCase\SortedFilesCase::BY_NAME),
                new BenchCase\ValuedCase('depth', 2),
                new BenchCase\ValuedCase('size', 2),
            )),
        );
    }

    public function getAdapters()
    {
        return array(
            new Adapter\PhpAdapter(),
            new Adapter\GnuFindAdapter(),
        );
    }

    protected function getDefaultCommands()
    {
        $commands = parent::getDefaultCommands();
        $commands[] = new Command\RunCommand();
        $commands[] = new Command\CaseCommand();

        return $commands;
    }

    protected function getDefaultHelperSet()
    {
        return new HelperSet(array(
            $formatter = new FormatterHelper(),
            new ReportHelper($formatter, $this->getTerminalWidth()),
        ));
    }
}
