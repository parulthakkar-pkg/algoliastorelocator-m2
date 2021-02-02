<?php
/**
 * Copyright © 2021 Neosoft Technologies. All rights reserved.
 */
namespace Algolia\Storelocator\Helper\Entity;

use Algolia\AlgoliaSearch\Helper\ConfigHelper;
use Algolia\Storelocator\Model\ResourceModel\Storelocator\CollectionFactory as StorelocatorCollectionFactory;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\DataObject;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\UrlFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class for StorelocatorInfo Helper.
 */
class StorelocatorInfoHelper
{
    /**
     * @var ManagerInterface
     */
    private $eventManager;

    /**
     * @var StorelocatorCollectionFactory
     */
    private $storelocatorCollectionFactory;

    /**
     * @var ConfigHelper
     */
    private $configHelper;

    /**
     * @var FilterProvider
     */
    private $filterProvider;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var UrlFactory
     */
    private $frontendUrlFactory;

    /**
     * PageHelper constructor.
     *
     * @param ManagerInterface      $eventManager
     * @param StorelocatorCollectionFactory $storelocatorCollectionFactory
     * @param ConfigHelper          $configHelper
     * @param FilterProvider        $filterProvider
     * @param StoreManagerInterface $storeManager
     * @param UrlFactory          $frontendUrlFactory
     */
    public function __construct(
        ManagerInterface $eventManager,
        StorelocatorCollectionFactory $storelocatorCollectionFactory,
        \Algolia\AlgoliaSearch\Helper\ConfigHelper $configHelper,
        FilterProvider $filterProvider,
        StoreManagerInterface $storeManager,
        UrlFactory $frontendUrlFactory
    ) {
        $this->eventManager = $eventManager;
        $this->storelocatorCollectionFactory = $storelocatorCollectionFactory;
        $this->configHelper = $configHelper;
        $this->filterProvider = $filterProvider;
        $this->storeManager = $storeManager;
        $this->frontendUrlFactory = $frontendUrlFactory;
    }

    public function getIndexNameSuffix()
    {
        return '_storeslocators';
    }

    public function getIndexSettings($storeId)
    {
        $indexSettings = [
            'searchableAttributes' => ['unordered(storelocator_id)',
                'unordered(store_title)','unordered(store_address)','unordered(store_zipcode)'],
            'attributesToSnippet'  => ['description:7'],
        ];

        $transport = new DataObject($indexSettings);
        $this->eventManager->dispatch(
            'algolia_storelocator_index_before_set_settings',
            ['store_id' => $storeId, 'index_settings' => $transport]
        );
        $indexSettings = $transport->getData();

        return $indexSettings;
    }

    public function getStorelocatorInfo($storeId, array $storelocatorIds = null)
    {
        $magentostorelocator = $this->storelocatorCollectionFactory->create()
                        ->addFieldToFilter('status', 1);

        if ($storelocatorIds && count($storelocatorIds)) {
            $magentostorelocator->addFieldToFilter('storelocator_id', ['in' => $storelocatorIds]);
        }
        $storelocators = [];

        $frontendUrlBuilder = $this->frontendUrlFactory->create()->setScope($storeId);

        /** @var Page $page */
        foreach ($magentostorelocator as $stores) {
            $storesObject = [];

            $storesObject['storelocator_id'] = $stores->getStorelocatorId();
            $storesObject['store_title'] = $stores->getStoreTitle();
            $storesObject['store_address'] = $stores->getStoreAddress();
            $storesObject['store_zipcode'] = $stores->getStoreZipcode();

            if (!$stores->getStorelocatorId()) {
                continue;
            }

            $storesObject['objectID'] = $stores->getStorelocatorId();

            $transport = new DataObject($storesObject);

            $storesObject = $transport->getData();

            $storelocators['toIndex'][] = $storesObject;
        }
        return $storelocators;
    }

    public function getStores($storeId = null)
    {
        $storeIds = [];

        if ($storeId === null) {
            /** @var \Magento\Store\Model\Store $store */
            foreach ($this->storeManager->getStores() as $store) {
                if ($this->configHelper->isEnabledBackEnd($store->getId()) === false) {
                    continue;
                }

                if ($store->getData('is_active')) {
                    $storeIds[] = $store->getId();
                }
            }
        } else {
            $storeIds = [$storeId];
        }

        return $storeIds;
    }

    private function strip($s, $completeRemoveTags = [])
    {
        if ($completeRemoveTags && $completeRemoveTags !== [] && $s) {
            $dom = new \DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML(mb_convert_encoding($s, 'HTML-ENTITIES', 'UTF-8'));
            libxml_use_internal_errors(false);

            $toRemove = [];
            foreach ($completeRemoveTags as $tag) {
                $removeTags = $dom->getElementsByTagName($tag);

                foreach ($removeTags as $item) {
                    $toRemove[] = $item;
                }
            }

            foreach ($toRemove as $item) {
                $item->parentNode->removeChild($item);
            }

            $s = $dom->saveHTML();
        }

        $s = html_entity_decode($s, null, 'UTF-8');

        $s = trim(preg_replace('/\s+/', ' ', $s));
        $s = preg_replace('/&nbsp;/', ' ', $s);
        $s = preg_replace('!\s+!', ' ', $s);
        $s = preg_replace('/\{\{[^}]+\}\}/', ' ', $s);
        $s = strip_tags($s);
        $s = trim($s);

        return $s;
    }
}
