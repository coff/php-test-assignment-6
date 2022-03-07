<?php

namespace Coff\TestAssignment\Application;

use Pimple\Psr11\Container;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

class Application extends \Symfony\Component\Console\Application implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /** @var Container */
    protected $container;

    /**
     * @return Container
     */
    public function getContainer(): Container
    {
        return $this->container;
    }

    /**
     * @param Container $container
     * @return Application
     */
    public function setContainer(Container $container): Application
    {
        $this->container = $container;
        return $this;
    }

}