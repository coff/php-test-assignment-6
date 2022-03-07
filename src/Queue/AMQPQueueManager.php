<?php

namespace Coff\TestAssignment\Queue;

use Coff\TestAssignment\Exception\QueueManagerException;
use Coff\TestAssignment\Listing\ListingEntry;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class AMQPQueueManager extends QueueManager
{
    protected AMQPStreamConnection $amqpConnection;
    protected AMQPChannel $amqpChannel;
    protected string $queueName;

    /**
     * @param string $queueName
     * @return self
     */
    public function setQueueName(string $queueName): self
    {
        $this->queueName = $queueName;
        return $this;
    }


    /**
     * @param AMQPStreamConnection $amqpConnection
     * @return self
     */
    public function setAmqpConnection(AMQPStreamConnection $amqpConnection): self
    {
        $this->amqpConnection = $amqpConnection;
        return $this;
    }

    /**
     * @param AMQPChannel $amqpChannel
     * @return self
     */
    public function setAmqpChannel(AMQPChannel $amqpChannel): self
    {
        $this->amqpChannel = $amqpChannel;
        return $this;
    }

    public function pushTour(ListingEntry $entry): self
    {
        $msg = new AMQPMessage(
            $this->serializer->serialize($entry, "json"),
            array('delivery_mode' => 2)
        );

        $this->amqpChannel->batch_basic_publish($msg, '', $this->queueName);

        return $this;
    }

    public function fetch(): self
    {
        try {
            $this->amqpChannel->publish_batch();
        } catch (\AMQPException $exception) {

            throw new QueueManagerException($exception->getMessage());
        }

        return $this;
    }

    public function close(): self
    {
        $this->amqpChannel->close();
        $this->amqpConnection->close();

        return $this;
    }
}