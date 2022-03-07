<?php

use Coff\TestAssignment\Command\Command;
use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\SerializerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Coff\TestAssignment\Application\Application;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Symfony\Component\Console\Event\ConsoleCommandEvent;

$container['application'] = function ($c) {
    $app = new Application('TourImporter', '0.1');
    $app->setCatchExceptions(false);
    $app->setContainer(new \Pimple\Psr11\Container($c));
    $app->setDispatcher($c['dispatcher']);
    return $app;
};

$container['queue_manager'] = function ($c) {
    $manager = new Coff\TestAssignment\Queue\AMQPQueueManager();
    $manager->setQueueName($c['message_queue:queue_name']);
    $manager->setAmqpChannel($c['message_queue:channel']);
    $manager->setAmqpConnection($c['message_queue:connection']);
    $manager->setSerializer($c['serializer:json']);
    return $manager;
};

$container['message_queue:queue_name'] = function ($c) {
    return 'tours_queue';
};

$container['message_queue:channel'] = function ($c) {
    $channel = $c['message_queue:connection']->channel();
    $channel->queue_declare(
        $c['message_queue:queue_name'],    #queue - Queue names may be up to 255 bytes of UTF-8 characters
        false,              #passive - can use this to check whether an exchange exists without modifying the server state
        true,               #durable, make sure that RabbitMQ will never lose our queue if a crash occurs - the queue will survive a broker restart
        false,              #exclusive - used by only one connection and the queue will be deleted when that connection closes
        false               #auto delete - queue is deleted when last consumer unsubscribes
    );
    return $channel;
};

$container['message_queue:connection'] = function ($c) {
    try {
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
    } catch (\PhpAmqpLib\Exception\AMQPIOException $exception) {
        $c['logger']->error('Is rabbitmq running? Unable to connect!');
        throw $exception;
    }
    return $connection;
};


$container['dispatcher'] = function ($c) {
    $dispatcher = new Symfony\Component\EventDispatcher\EventDispatcher();
    $dispatcher->addListener(\Symfony\Component\Console\ConsoleEvents::COMMAND, function (ConsoleCommandEvent $event) use ($c) {
       $c['running_command'] = $event->getCommand();

       /** @var Monolog\Logger $logger */
       $logger = $c['logger'];
       $logger->debug('Running command ' . $event->getCommand()->getName());
    });

    return $dispatcher;
};

$container['serializer:json'] = function ($c) {
    $sBuilder = SerializerBuilder::create();
    $sBuilder->setPropertyNamingStrategy(new IdenticalPropertyNamingStrategy());
    return $sBuilder->build();
};

$container['logger'] = function ($c) {

    if (isset($c['running_command']) && $c['running_command'] instanceof Command) {
        /** @var Command $command  */
        $command = $c['running_command'];

        /* each command should tell us its logfile name */
        $logFileName = $command->getLogFilename();
    }

    if (empty($logFileName) || false === ($res = @fopen($logFileName, 'a'))) {
        $handler = new StreamHandler('php://stderr', Logger::DEBUG);
    } else {
        fclose($res);
        $handler = new StreamHandler($logFileName, Logger::DEBUG);
    }
    $logger = new Logger('default');
    $logger->pushHandler($handler);
    $logger->debug('Logger initialized');
    return $logger;
};