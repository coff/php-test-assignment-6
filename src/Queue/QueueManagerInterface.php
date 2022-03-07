<?php

namespace Coff\TestAssignment\Queue;

use Coff\TestAssignment\Listing\ListingEntry;

interface QueueManagerInterface
{
    public function pushTour(ListingEntry $tour) : self;

    public function fetch() : self;

    public function close() : self;
}