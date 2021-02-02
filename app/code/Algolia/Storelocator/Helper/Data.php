<?php
/**
 * Copyright © 2021 Neosoft Technologies. All rights reserved.
 */
namespace Algolia\Storelocator\Helper;

use Algolia\AlgoliaSearch\Helper\AlgoliaHelper;
use Algolia\AlgoliaSearch\Helper\ConfigHelper;
use Algolia\AlgoliaSearch\Helper\Entity\AdditionalSectionHelper;
use Algolia\AlgoliaSearch\Helper\Entity\CategoryHelper;
use Algolia\AlgoliaSearch\Helper\Entity\PageHelper;
use Algolia\AlgoliaSearch\Helper\Entity\ProductHelper;
use Algolia\AlgoliaSearch\Helper\Entity\SuggestionHelper;
use Algolia\AlgoliaSearch\Helper\Logger;
use Algolia\Storelocator\Helper\Entity\StorelocatorInfoHelper;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Framework\App\Config\ScopeCodeResolver;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Event\ManagerInterface;
use Magento\Store\Model\App\Emulation;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class for Helper for storelocator.
 */
class Data extends \Algolia\AlgoliaSearch\Helper\Data
{
    private $algoliaHelper;
    private $pageHelper;
    private $categoryHelper;
    private $productHelper;
    private $suggestionHelper;
    private $additionalSectionHelper;
    private $stockRegistry;

    private $logger;
    private $configHelper;
    private $emulation;
    private $resource;
    private $eventManager;
    private $scopeCodeResolver;
    private $storeManager;
    private $storeLocatorHelper;

    private $emulationRuns = false;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;
    /**
     * @var StorelocatorInfoHelper
     */
    protected $storelocatorHelper;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        AlgoliaHelper $algoliaHelper,
        ConfigHelper $configHelper,
        ProductHelper $producthelper,
        CategoryHelper $categoryHelper,
        PageHelper $pageHelper,
        SuggestionHelper $suggestionHelper,
        AdditionalSectionHelper $additionalSectionHelper,
        StockRegistryInterface $stockRegistry,
        Emulation $emulation,
        Logger $logger,
        ResourceConnection $resource,
        ManagerInterface $eventManager,
        ScopeCodeResolver $scopeCodeResolver,
        StoreManagerInterface $storeManager,
        StorelocatorInfoHelper $storelocatorHelper
    ) {
        $this->algoliaHelper = $algoliaHelper;
        $this->pageHelper = $pageHelper;
        $this->categoryHelper = $categoryHelper;
        $this->productHelper = $producthelper;
        $this->suggestionHelper = $suggestionHelper;
        $this->additionalSectionHelper = $additionalSectionHelper;
        $this->stockRegistry = $stockRegistry;
        $this->configHelper = $configHelper;
        $this->logger = $logger;
        $this->emulation = $emulation;
        $this->resource = $resource;
        $this->eventManager = $eventManager;
        $this->scopeCodeResolver = $scopeCodeResolver;
        $this->storeManager = $storeManager;
        $this->storelocatorHelper = $storelocatorHelper;
        parent::__construct(
            $algoliaHelper,
            $configHelper,
            $producthelper,
            $categoryHelper,
            $pageHelper,
            $suggestionHelper,
            $additionalSectionHelper,
            $stockRegistry,
            $emulation,
            $logger,
            $resource,
            $eventManager,
            $scopeCodeResolver,
            $storeManager
        );
    }
    public function rebuildStoreLocatorIndex($storeId, array $storelocatorIds = null)
    {
        if ($this->isIndexingEnabled($storeId) === false) {
            return;
        }
        $indexName = $this->getIndexName($this->storelocatorHelper->getIndexNameSuffix(), $storeId);

        $this->startEmulation($storeId);

        $storelocators = $this->storelocatorHelper->getStorelocatorInfo($storeId, $storelocatorIds);

        $this->stopEmulation();

        // if there are storelocatorIds defined, do not index to _tmp
        $isFullReindex = (!$storelocatorIds);

        if (isset($storelocators['toIndex']) && count($storelocators['toIndex'])) {
            $storelocatorsToIndex = $storelocators['toIndex'];
            $toIndexName = $indexName . ($isFullReindex ? '_tmp' : '');

            foreach (array_chunk($storelocatorsToIndex, 100) as $chunk) {
                try {
                    $this->algoliaHelper->addObjects($chunk, $toIndexName);
                } catch (\Exception $e) {
                    $this->logger->log($e->getMessage());
                    continue;
                }
            }
        }

        if (!$isFullReindex && isset($storelocators['toRemove']) && count($storelocators['toRemove'])) {
            $storelocatorsToRemove = $storelocators['toRemove'];

            foreach (array_chunk($storelocatorsToRemove, 100) as $chunk) {
                try {
                    $this->algoliaHelper->deleteObjects($chunk, $indexName);
                } catch (\Exception $e) {
                    $this->logger->log($e->getMessage());
                    continue;
                }
            }
        }

        if ($isFullReindex) {
            $this->algoliaHelper->copyQueryRules($indexName, $indexName . '_tmp');
            $this->algoliaHelper->moveIndex($indexName . '_tmp', $indexName);
        }

        $this->algoliaHelper->setSettings($indexName, $this->storelocatorHelper->getIndexSettings($storeId));
    }
}
