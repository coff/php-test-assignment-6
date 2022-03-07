<?php

namespace Coff\TestAssignment\Listing;

use Coff\TestAssignment\Connector\Connector;

interface ListingImporterInterface
{
    public function setMaxBatchSize(int $maxBatchSize) : self;

    public function enqueueNextBatch() : ?int;
}