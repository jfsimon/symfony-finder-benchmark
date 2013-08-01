<?php

namespace FinderBench\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use FinderBench\CaseRunner;

/**
 * @author Jean-François Simon <jeanfrancois.simon@sensiolabs.com>
 */
class CaseCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('case')
            ->setDescription('Runs bench case.')
            ->addArgument('index', InputArgument::REQUIRED, 'Case index')
            ->addArgument('iterations', InputArgument::REQUIRED, 'Runner iterations')
            ->addArgument('root', InputArgument::REQUIRED, 'Workspace root dir')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cases = $this->getApplication()->getCases();
        $index = $input->getArgument('index');

        if (!isset($cases[$index])) {
            throw new \InvalidArgumentException('Case #'.$index.' does not exist.');
        }

        $runner = new CaseRunner($input->getArgument('iterations'), $input->getArgument('root'));
        $report = $runner->run($cases[$index], $this->getApplication()->getAdapters());

        $output->write($this->getHelper('report')->formatCase($report, $index));
    }
}
