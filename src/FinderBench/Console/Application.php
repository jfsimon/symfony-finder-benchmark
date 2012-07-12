<?php

namespace FinderBench\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use FinderBench\Command\RunCommand;

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
            )));
        }

        return parent::run($input, $output);
    }

    protected function getDefaultCommands()
    {
        $commands = parent::getDefaultCommands();
        $commands[] = new RunCommand();

        return $commands;
    }

    protected function getDefaultHelperSet()
    {
        return array(
            new FormatterHelper(),
            new ReportHelper($this->getTerminalWidth()),
        );
    }
}
