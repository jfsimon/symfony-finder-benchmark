<?php

namespace FinderBench\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Adapter;
use FinderBench\FilesTree;
use FinderBench\CaseRunner;
use FinderBench\BenchCase;
use Symfony\Component\Process\Process;

/**
 * @author Jean-François Simon <jeanfrancois.simon@sensiolabs.com>
 */
class RunCommand extends Command
{
    const PROCESS_TIMEOUT = 600;

    protected function configure()
    {
        $this
            ->setName('run')
            ->setDescription('Runs benchmark.')
            ->addOption('root',       'r', InputOption::VALUE_REQUIRED, 'Workspace root dir', sys_get_temp_dir().DIRECTORY_SEPARATOR.'finder-bench')
            ->addOption('size',       's', InputOption::VALUE_REQUIRED, 'Files tree size',    10)
            ->addOption('depth',      'd', InputOption::VALUE_REQUIRED, 'Files tree depth',   3)
            ->addOption('iterations', 'i', InputOption::VALUE_REQUIRED, 'Bench iterations',   10)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $files = new FilesTree($input->getOption('root'),$input->getOption('size'), $input->getOption('depth'));

        $this->process(sprintf('init %s %s %s', $input->getOption('root'), $input->getOption('size'), $input->getOption('depth')), $output);

        $output->write($this->getHelper('report')->formatDetails(array(
            'files'       => $files->getFilesCount(),
            'directories' => $files->getDirsCount(),
            'iterations'  => $input->getOption('iterations'),
            'cases'       => count($this->getApplication()->getCases()),
            'adapters'    => count($this->getApplication()->getAdapters()),
        )));

        $output->write($this->getHelper('report')->formatHeader($this->getApplication()->getAdapters()));

        foreach (array_keys($this->getApplication()->getCases()) as $index) {
            $this->process(sprintf('case %s %s %s', $index, $input->getOption('iterations'),$input->getOption('root')), $output);
        }

        $output->write($this->getHelper('report')->formatCases($this->getApplication()->getCases()));

        $this->process(sprintf('finalize %s', $input->getOption('root')), $output);
    }

    private function process($command, $output)
    {
        $process = new Process('bin/benchmark '.$command, realpath(__DIR__.'/../../..'), null, null, self::PROCESS_TIMEOUT);
        $process->run(function($type, $out) use ($output) {
            $output->write($out);
        });
    }
}
