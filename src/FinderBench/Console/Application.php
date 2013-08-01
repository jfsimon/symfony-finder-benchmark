<?php

namespace FinderBench\Console;

use FinderBench\Profiler;
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
    private $cases;
    private $adapters;

    public function __construct()
    {
        parent::__construct('FinderBench', 'Beta');

        $this->cases = array(

            // name
            new BenchCase\NamedCase(array('a*'), array()),
            new BenchCase\NamedCase(array('~^a~'), array()),
            new BenchCase\NamedCase(array('a*'), array('*a')),
            new BenchCase\NamedCase(array('~^a.*~'), array('~.*a$~')),
            new BenchCase\NamedCase(array('ab*'), array('*ba')),
            new BenchCase\NamedCase(array('~^ab.*~'), array('~.*ba$~')),

            // values
            new BenchCase\ValuedCase('depth', 1, 3),
            new BenchCase\ValuedCase('depth', 2, 2),
            new BenchCase\ValuedCase('size', 1, 3),
            new BenchCase\ValuedCase('size', 2, 2),

            // sorts
            new BenchCase\SortedFilesCase(BenchCase\SortedFilesCase::BY_ACCESSED),
            new BenchCase\SortedFilesCase(BenchCase\SortedFilesCase::BY_CHANGED),
            new BenchCase\SortedFilesCase(BenchCase\SortedFilesCase::BY_MODIFIED),
            new BenchCase\SortedFilesCase(BenchCase\SortedFilesCase::BY_NAME),
            new BenchCase\SortedFilesCase(BenchCase\SortedFilesCase::BY_TYPE),

            // content
            new BenchCase\ContainingCase(array('~^a~'), array()),
            new BenchCase\ContainingCase(array('~^a.*~'), array('~.*a$~')),
            new BenchCase\ContainingCase(array('~^ab.*~'), array('~.*ba$~')),

//            // composed
//            new BenchCase\ComposedCase(array(
//                new BenchCase\NamedCase(array('~^a.*~'), array('~.*a$~')),
//                new BenchCase\SortedFilesCase(BenchCase\SortedFilesCase::BY_MODIFIED),
//            )),
//            new BenchCase\ComposedCase(array(
//                new BenchCase\NamedCase(array('~^ab.*~'), array('~.*ba$~')),
//                new BenchCase\SortedFilesCase(BenchCase\SortedFilesCase::BY_NAME),
//                new BenchCase\ValuedCase('depth', 1, 3),
//                new BenchCase\ValuedCase('size', 1, 20),
//            )),
//            new BenchCase\ComposedCase(array(
//                new BenchCase\ContainingCase(array('~^a.*~'), array('~.*a$~')),
//                new BenchCase\SortedFilesCase(BenchCase\SortedFilesCase::BY_MODIFIED),
//            )),
//            new BenchCase\ComposedCase(array(
//                new BenchCase\NamedCase(array('~^ab.*~'), array('~.*ba$~')),
//                new BenchCase\ContainingCase(array('~^ab.*~'), array('~.*ba$~')),
//                new BenchCase\SortedFilesCase(BenchCase\SortedFilesCase::BY_MODIFIED),
//                new BenchCase\ValuedCase('depth', 1, 3),
//            )),
//            new BenchCase\ComposedCase(array(
//                new BenchCase\NamedCase(array('~^ab.*~'), array('~.*ba$~')),
//                new BenchCase\ContainingCase(array('~^ab.*~'), array('~.*ba$~')),
//                new BenchCase\SortedFilesCase(BenchCase\SortedFilesCase::BY_NAME),
//                new BenchCase\ValuedCase('depth', 1, 3),
//                new BenchCase\ValuedCase('size', 1, 20),
//            )),
        );

        $profiler = new Profiler(__DIR__.'/../../../profiles');
        foreach ($this->cases as $case) {
            $case->profile($profiler);
        }

        $this->adapters = array(
            new Adapter\PhpAdapter(),
            new Adapter\RecursivePhpAdapter(),
            new Adapter\GnuFindAdapter(),
        );
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
        return $this->cases;
    }

    public function getAdapters()
    {
        return $this->adapters;
    }

    protected function getDefaultCommands()
    {
        $commands = parent::getDefaultCommands();
        $commands[] = new Command\RunCommand();
        $commands[] = new Command\InitCommand();
        $commands[] = new Command\CaseCommand();
        $commands[] = new Command\FinalizeCommand();

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
