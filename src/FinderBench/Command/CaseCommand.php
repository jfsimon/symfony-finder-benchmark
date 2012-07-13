<?php

namespace FinderBench\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
            ->addArgument('class', InputArgument::REQUIRED, 'Case class')
            ->addArgument('args',  InputArgument::IS_ARRAY, 'Case arguments')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $refl  = new ReflectionClass($input->getArgument('arguments'));
        $times = $refl->newInstanceArgs($input->getArgument('arguments'));

        $this
            ->addReport($bench->buildReport(), $cases, $adapters)
            ->write($output);
    }
}
