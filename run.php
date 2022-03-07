#!/usr/bin/env php
<?php

namespace Coff\TestAssignment;

use Coff\TestAssignment\Application\Application;
use Coff\TestAssignment\Command\ImportListingsCommand;
use PhpAmqpLib\Exception\AMQPConnectionClosedException;
use Pimple\Container;

require __DIR__ . '/vendor/autoload.php';

$container = new Container();

try {
    require (__DIR__ . '/bootstrap/common.php');
    require (__DIR__ . '/bootstrap/importers.php');
    /**
     * @var Application $app
     */
    $app = $container['application'];

    $app->add(new ImportListingsCommand());

    $app->run();
} catch (\Exception $exception) {
    $container['logger']->error($exception->getMessage());
    exit(1);
}

