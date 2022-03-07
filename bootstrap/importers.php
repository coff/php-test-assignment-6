<?php

use Coff\TestAssignment\Listing\GenericListingImporter;

$container['importer:generic'] = function ($c) {
    $operator = new GenericListingImporter();
    $operator->setOperatorRef('generic');
    $operator->setConnector(new \Coff\TestAssignment\Connector\GenericConnector());
    // Maximum batch size limits response query size
    $operator->setMaxBatchSize(7);
    return $operator;
};

$container['importer:generic_rest'] = function ($c) {
    $operator = new GenericListingImporter();
    $operator->setOperatorRef('generic_rest');
    $operator->setConnector(new \Coff\TestAssignment\Connector\GenericConnector());
    // Maximum batch size limits response query size
    $operator->setMaxBatchSize(7);
    return $operator;
};