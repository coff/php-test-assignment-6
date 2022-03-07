<?php

namespace Coff\TestAssignment\Command;

use Coff\TestAssignment\Application\Application;
use Coff\TestAssignment\Exception\ConnectorException;
use Coff\TestAssignment\Exception\Exception;
use Coff\TestAssignment\Exception\QueueManagerException;
use Coff\TestAssignment\Listing\ListingImporter;
use Pimple\Psr11\Container;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportListingsCommand extends Command
{
    protected int $maxFetchFails;

    protected int $maxEnqueueFails;

    public function configure()
    {
        parent::configure();

        $this
            ->setName('import:listings')
            ->addOption("max-fetch-fails", "f", InputOption::VALUE_OPTIONAL, "Sets maximum allowed failed tour fetches", 3)
            ->addOption("max-enqueue-fails", "e", InputOption::VALUE_OPTIONAL, "Sets max allowed failed tour enqueues", 3)
            ->addArgument('operator_name',InputArgument::REQUIRED, "Tour operator name to import listings from")
            ->setDescription('Imports tour listings for given operator and queues tours for further processing')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $this->maxFetchFails = $input->getOption('max-fetch-fails');
        $this->maxEnqueueFails = $input->getOption('max-enqueue-fails');

        $operatorName = $input->getArgument('operator_name');

        /** @var Application $app */
        $app = $this->getApplication();

        /** @var Container $container */
        $container = $app->getContainer();

        $containerEntry = 'importer:' . $operatorName;

        if (false === $container->has($containerEntry)) {
            $this->logger->error('Can\'t find operator by specified name ' . $operatorName . '. Container object ' . $containerEntry . ' doesn\'t seem to exist.');
            return 1;
        }

        /** @var ListingImporter $listingImporter */
        $listingImporter = $container->get($containerEntry);
        $listingImporter->setQueueManager($container->get('queue_manager'));

        $i = 0; $fetchFailed = 0; $enqueueFailed = 0;

        do {
            try {
                $batchSize = $listingImporter->enqueueNextBatch();

                if ($batchSize > 0) {
                    $this->logger->info('Fetched and enqueued batch no ' . ++$i . ' of ' . $batchSize . ' tours from ' . $operatorName);
                }
            } catch (ConnectorException $exception) {
                $this->logger->error($exception->getMessage());
                $fetchFailed++; $batchSize = true;

                if ($fetchFailed > $this->maxFetchFails) {
                    $this->logger->error('Aborting due to too many failed fetches');
                    return 1;
                }
            } catch (QueueManagerException $exception) {
                $this->logger->error($exception->getMessage());
                $enqueueFailed++; $batchSize = true;

                if ($enqueueFailed > $this->maxEnqueueFails) {
                    $this->logger->error('Aborting due to too many failed enqueues');
                    return 1;
                }
            }


        } while ($batchSize);

        return 0;
    }
}