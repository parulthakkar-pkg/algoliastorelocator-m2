<?php
/**
 * Copyright © 2021 Neosoft Technologies. All rights reserved.
 */
namespace Algolia\Storelocator\Model\Indexer;

use Algolia\AlgoliaSearch\Helper\ConfigHelper;
use Algolia\AlgoliaSearch\Helper\Data;
use Algolia\AlgoliaSearch\Model\Queue;
use Magento\Framework\Message\ManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Symfony\Component\Console\Output\ConsoleOutput;

class Storelocator implements \Magento\Framework\Indexer\ActionInterface, \Magento\Framework\Mview\ActionInterface
{
    protected $storelocatorHelperdata;
    public function __construct(
        StoreManagerInterface $storeManager,
        \Algolia\Storelocator\Helper\Entity\StorelocatorInfoHelper $storeLocatorHelper,
        Data $helper,
        \Algolia\AlgoliaSearch\Helper\AlgoliaHelper $algoliaHelper,
        Queue $queue,
        ConfigHelper $configHelper,
        ManagerInterface $messageManager,
        ConsoleOutput $output,
        \Algolia\Storelocator\Helper\Data $storelocatorHelperdata
    ) {
        $this->fullAction = $helper;
        $this->storeManager = $storeManager;
        $this->storeLocatorHelper = $storeLocatorHelper;
        $this->algoliaHelper = $algoliaHelper;
        $this->queue = $queue;
        $this->configHelper = $configHelper;
        $this->messageManager = $messageManager;
        $this->output = $output;
        $this->storelocatorHelperdata = $storelocatorHelperdata;
    }
    /*
     * Used by mview, allows process indexer in the "Update on schedule" mode
     */
    public function execute($ids)
    {
        if (!$this->configHelper->getApplicationID()
            || !$this->configHelper->getAPIKey()
            || !$this->configHelper->getSearchOnlyAPIKey()) {
            $errorMessage = 'Algolia reindexing failed:
                You need to configure your Algolia credentials in Stores > Configuration > Algolia Search.';

            if (php_sapi_name() === 'cli') {
                $this->output->writeln($errorMessage);

                return;
            }

            $this->messageManager->addErrorMessage($errorMessage);

            return;
        }

        $storeIds = $this->storeLocatorHelper->getStores();

        foreach ($storeIds as $storeId) {
            if ($this->fullAction->isIndexingEnabled($storeId) === false) {
                continue;
            }
            //if ($this->isPagesInAdditionalSections($storeId)) {
            $data = ['store_id' => $storeId];
            if (is_array($ids) && count($ids) > 0) {
                $data['storelocator_ids'] = $ids;
            }

            $this->queue->addToQueue(
                $this->storelocatorHelperdata,
                'rebuildStoreLocatorIndex',
                $data,
                is_array($ids) ? count($ids) : 1
            );
            //}
        }
    }

    /*
     * Will take all of the data and reindex
     * Will run when reindex via command line
     */
    public function executeFull()
    {
        $this->execute(null);
    }

    public function executeList(array $ids)
    {
        $this->execute($ids);
    }

    public function executeRow($id)
    {
        $this->execute([$id]);
    }
}
