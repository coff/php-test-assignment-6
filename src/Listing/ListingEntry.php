<?php

namespace Coff\TestAssignment\Listing;

use JMS\Serializer\Annotation\Type;

class ListingEntry
{
    /** @Type("string")  */
    protected $operatorRef;

    /** @Type("string") */
    protected $tourRef;

    /**
     * @param mixed $operatorRef
     * @return ListingEntry
     */
    public function setOperatorRef($operatorRef)
    {
        $this->operatorRef = $operatorRef;
        return $this;
    }

    /**
     * @param mixed $tourRef
     * @return ListingEntry
     */
    public function setTourRef($tourRef)
    {
        $this->tourRef = $tourRef;
        return $this;
    }
}