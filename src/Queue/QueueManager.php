<?php

namespace Coff\TestAssignment\Queue;

use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerAwareTrait;

abstract class QueueManager implements QueueManagerInterface {

    use LoggerAwareTrait;

    /** @var SerializerInterface */
    protected $serializer;

    /**
     * @param SerializerInterface $serializer
     */
    public function setSerializer(SerializerInterface $serializer): void
    {
        $this->serializer = $serializer;
    }

}