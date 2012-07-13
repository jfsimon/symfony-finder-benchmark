<?php

namespace FinderBench\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use FinderBench\FilesTree;

/**
 * @author Jean-François Simon <jeanfrancois.simon@sensiolabs.com>
 */
class InitCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('init')
            ->setDescription('Initializes benchmark.')
            ->addArgument('root',  InputArgument::REQUIRED, 'Workspace root dir')
            ->addArgument('size',  InputArgument::REQUIRED, 'Files tree size')
            ->addArgument('depth', InputArgument::REQUIRED, 'Files tree depth')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $files = new FilesTree($input->getArgument('root'), $input->getArgument('size'), $input->getArgument('depth'));
        $files->build();
    }
}
