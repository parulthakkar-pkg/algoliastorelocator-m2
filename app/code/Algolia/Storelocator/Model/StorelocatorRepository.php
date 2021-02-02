<?php
/**
 * Copyright © 2021 Neosoft Technologies. All rights reserved.
 */
declare(strict_types=1);

namespace Algolia\Storelocator\Model;

use Algolia\Storelocator\Api\Data\StorelocatorInterfaceFactory;
use Algolia\Storelocator\Api\Data\StorelocatorSearchResultsInterfaceFactory;
use Algolia\Storelocator\Api\StorelocatorRepositoryInterface;
use Algolia\Storelocator\Model\ResourceModel\Storelocator as ResourceStorelocator;
use Algolia\Storelocator\Model\ResourceModel\Storelocator\CollectionFactory as StorelocatorCollectionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;

class StorelocatorRepository implements StorelocatorRepositoryInterface
{

    protected $resource;

    protected $storelocatorFactory;

    protected $storelocatorCollectionFactory;

    protected $searchResultsFactory;

    protected $dataObjectHelper;

    protected $dataObjectProcessor;

    protected $dataStorelocatorFactory;

    protected $extensionAttributesJoinProcessor;

    private $storeManager;

    private $collectionProcessor;

    protected $extensibleDataObjectConverter;

    /**
     * @param ResourceStorelocator $resource
     * @param StorelocatorFactory $storelocatorFactory
     * @param StorelocatorInterfaceFactory $dataStorelocatorFactory
     * @param StorelocatorCollectionFactory $storelocatorCollectionFactory
     * @param StorelocatorSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceStorelocator $resource,
        StorelocatorFactory $storelocatorFactory,
        StorelocatorInterfaceFactory $dataStorelocatorFactory,
        StorelocatorCollectionFactory $storelocatorCollectionFactory,
        StorelocatorSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->storelocatorFactory = $storelocatorFactory;
        $this->storelocatorCollectionFactory = $storelocatorCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataStorelocatorFactory = $dataStorelocatorFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Algolia\Storelocator\Api\Data\StorelocatorInterface $storelocator
    ) {
        /* if (empty($storelocator->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $storelocator->setStoreId($storeId);
        } */

        $storelocatorData = $this->extensibleDataObjectConverter->toNestedArray(
            $storelocator,
            [],
            \Algolia\Storelocator\Api\Data\StorelocatorInterface::class
        );

        $storelocatorModel = $this->storelocatorFactory->create()->setData($storelocatorData);

        try {
            $this->resource->save($storelocatorModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the storelocator: %1',
                $exception->getMessage()
            ));
        }
        return $storelocatorModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function get($storelocatorId)
    {
        $storelocator = $this->storelocatorFactory->create();
        $this->resource->load($storelocator, $storelocatorId);
        if (!$storelocator->getId()) {
            throw new NoSuchEntityException(__('storelocator with id "%1" does not exist.', $storelocatorId));
        }
        return $storelocator->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->storelocatorCollectionFactory->create();

        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Algolia\Storelocator\Api\Data\StorelocatorInterface::class
        );

        $this->collectionProcessor->process($criteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $items = [];
        foreach ($collection as $model) {
            $items[] = $model->getDataModel();
        }

        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \Algolia\Storelocator\Api\Data\StorelocatorInterface $storelocator
    ) {
        try {
            $storelocatorModel = $this->storelocatorFactory->create();
            $this->resource->load($storelocatorModel, $storelocator->getStorelocatorId());
            $this->resource->delete($storelocatorModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the storelocator: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($storelocatorId)
    {
        return $this->delete($this->get($storelocatorId));
    }
}
