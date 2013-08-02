<?php

namespace FinderBench\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\TableHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Jean-François Simon <jeanfrancois.simon@sensiolabs.com>
 */
class ProfileCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('profile')
            ->setDescription('Display a profile file.')
            ->addArgument('filename', InputArgument::REQUIRED, 'The profile filename')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $this->getApplication()->getProfilerDir().DIRECTORY_SEPARATOR.$input->getArgument('filename');

        if (!file_exists($file)) {
            throw new \InvalidArgumentException(sprintf('Profile "%s" not found.', $input->getArgument('filename')));
        }

        /** @var TableHelper $table */
        $table = $this->getHelper('table');
        $table->setHeaders(array('function', 'calls', 'time'));

        $data = unserialize(file_get_contents($file));
        uasort($data, function (array $a, array $b) { return $a['wt'] > $b['wt']; });
        foreach ($data as $name => $measure) {
            $table->addRow(array(preg_replace('~([^=]+==>)?([^@]+)$~', '$2', $name), $measure['ct'], $measure['wt']));
        }

        $table->render($output);
    }
}
