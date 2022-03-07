<?php

namespace Coff\TestAssignment\Command;

use Pimple\Psr11\Container;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Command extends \Symfony\Component\Console\Command\Command
{
    use LoggerAwareTrait;

    protected string $logFilename = '';

    /**
     *  Returns log file name
     */
    public function getLogFilename() : string {
        return $this->logFilename;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var Container $c */
        $c = $this->getApplication()->getContainer();


        $this->setLogger($c->get('logger'));

        return 0;
    }
}