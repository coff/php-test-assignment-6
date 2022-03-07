<?php

namespace Coff\TestAssignment\Listing;

use Coff\TestAssignment\Connector\GenericConnector;
use Coff\TestAssignment\Exception\QueueManagerException;

class GenericListingImporter extends ListingImporter implements ListingImporterInterface
{
    use ListingImporterTrait;

    protected int $offset = 0;
    protected ?GenericConnector $connector;

    public function setConnector(GenericConnector $connector)
    {
        $this->connector = $connector;
    }

    /**
     * @return int|null
     * @throws QueueManagerException
     */
    public function enqueueNextBatch() : ?int
    {
        $tours = $this->connector->getTours($this->offset, $this->maxBatchSize);

        if( empty($tours) ) {
            return null;
        }

        foreach ($tours as $tour) {
            $entry = new ListingEntry();
            $entry
                ->setOperatorRef($this->operatorRef)
                ->setTourRef($tour);


                $this->queueManager->pushTour($entry);

        }

        $this->queueManager->fetch();

        // it only increases offset if there's no exception thrown above
        $this->offset+= $this->maxBatchSize;

        return count($tours);
    }
}