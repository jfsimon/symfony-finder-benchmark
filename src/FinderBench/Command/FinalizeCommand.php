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
class FinalizeCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('finalize')
            ->setDescription('Finalizes benchmark.')
            ->addArgument('root',  InputArgument::REQUIRED, 'Workspace root dir')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $files = new FilesTree($input->getArgument('root'), 0, 0);
        $files->remove();
    }
}
