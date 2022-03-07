<?php

namespace Coff\TestAssignment\Listing;

trait ListingImporterTrait
{
    protected ?int $maxBatchSize;

    public function setMaxBatchSize(int $maxBatchSize) : self
    {
        $this->maxBatchSize = $maxBatchSize;

        return $this;
    }

    public function getMaxBatchSize()
    {
        return $this->maxBatchSize;
    }
}