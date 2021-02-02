<?php
/**
 * Copyright © 2021 Neosoft Technologies. All rights reserved.
 */
declare(strict_types=1);

namespace Algolia\Storelocator\Model;

use Algolia\Storelocator\Api\Data\StorelocatorInterface;
use Algolia\Storelocator\Api\Data\StorelocatorInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

class Storelocator extends \Magento\Framework\Model\AbstractModel
{

    protected $storelocatorDataFactory;

    protected $dataObjectHelper;

    protected $_eventPrefix = 'algolia_storelocator_storelocator';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param StorelocatorInterfaceFactory $storelocatorDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Algolia\Storelocator\Model\ResourceModel\Storelocator $resource
     * @param \Algolia\Storelocator\Model\ResourceModel\Storelocator\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        StorelocatorInterfaceFactory $storelocatorDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Algolia\Storelocator\Model\ResourceModel\Storelocator $resource,
        \Algolia\Storelocator\Model\ResourceModel\Storelocator\Collection $resourceCollection,
        array $data = []
    ) {
        $this->storelocatorDataFactory = $storelocatorDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve storelocator model with storelocator data
     * @return StorelocatorInterface
     */
    public function getDataModel()
    {
        $storelocatorData = $this->getData();

        $storelocatorDataObject = $this->storelocatorDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $storelocatorDataObject,
            $storelocatorData,
            StorelocatorInterface::class
        );

        return $storelocatorDataObject;
    }
}
