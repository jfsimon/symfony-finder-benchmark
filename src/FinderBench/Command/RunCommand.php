<?php

namespace FinderBench\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Adapter;
use FinderBench\FinderBench;
use FinderBench\FilesTree;
use FinderBench\CaseRunner;
use FinderBench\BenchCase;

/**
 * @author Jean-François Simon <jeanfrancois.simon@sensiolabs.com>
 */
class RunCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('run')
            ->setDescription('Runs benchmark.')

            ->addOption('root',       'r', InputOption::VALUE_REQUIRED, 'Workspace root dir', sys_get_temp_dir().DIRECTORY_SEPARATOR.'finder-bench')
            ->addOption('size',       's', InputOption::VALUE_REQUIRED, 'Files tree size',    2)
            ->addOption('depth',      'd', InputOption::VALUE_REQUIRED, 'Files tree depth',   4)
            ->addOption('iterations', 'i', InputOption::VALUE_REQUIRED, 'Bench iterations',   1)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $runner = new CaseRunner($input->getOption('iterations'), $input->getOption('root'));
        $files  = new FilesTree($input->getOption('root'), $input->getOption('size'), $input->getOption('depth'));
        $bench  = new FinderBench($files, $runner);

        foreach ($cases = $this->getCases() as $case) {
            $bench->registerCase($case);
        }

        foreach ($adapters = $this->getAdapters() as $adapter) {
            $bench->registerAdapter($adapter);
        }

        $this->getHelper('report')
            ->formatDetails($output, $bench)
            ->formatHeader($output, $adapters)
            ->formatReport($output, $bench->buildReport(), $cases, $adapters);
    }

    private function getCases()
    {
        return array(
            new BenchCase\NameContainsCase(array('a*'), array()),
        );
    }

    private function getAdapters()
    {
        return array(
            new Adapter\PhpAdapter(),
            new Adapter\GnuFindAdapter(),
        );
    }
}
