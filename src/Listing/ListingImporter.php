<?php

namespace Coff\TestAssignment\Listing;

use Coff\TestAssignment\Queue\QueueManager;

abstract class ListingImporter
{

    protected string $operatorRef;

    protected QueueManager $queueManager;

    /**
     * @param QueueManager $queueManager
     * @return self
     */
    public function setQueueManager(QueueManager $queueManager): self
    {
        $this->queueManager = $queueManager;

        return $this;
    }

    /**
     * @param string $operatorRef
     * @return self
     */
    public function setOperatorRef(string $operatorRef): self
    {
        $this->operatorRef = $operatorRef;

        return $this;
    }


}