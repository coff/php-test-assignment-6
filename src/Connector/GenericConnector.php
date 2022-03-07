<?php

namespace Coff\TestAssignment\Connector;

use Coff\TestAssignment\Exception\ConnectorException;

class GenericConnector extends Connector
{
    protected $listings = [
      1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26
    ];

    public function getTours($offset, $length) {

        // little failure simulation just to spice it up
        if (rand(0,3) == 1) {
            throw new ConnectorException('Fetch failed (simulated) for offset ' . $offset );
        }

        return array_slice($this->listings, $offset, $length);
    }
}