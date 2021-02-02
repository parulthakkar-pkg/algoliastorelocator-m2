<?php
/**
 * Copyright © 2021 Neosoft Technologies. All rights reserved.
 */
declare(strict_types=1);

namespace Algolia\Storelocator\Model\Data;

use Algolia\Storelocator\Api\Data\StorelocatorInterface;

class Storelocator extends \Magento\Framework\Api\AbstractExtensibleObject implements StorelocatorInterface
{

    /**
     * Get storelocator_id
     * @return string|null
     */
    public function getStorelocatorId()
    {
        return $this->_get(self::STORELOCATOR_ID);
    }

    /**
     * Set storelocator_id
     * @param string $storelocatorId
     * @return \Algolia\Storelocator\Api\Data\StorelocatorInterface
     */
    public function setStorelocatorId($storelocatorId)
    {
        return $this->setData(self::STORELOCATOR_ID, $storelocatorId);
    }

    /**
     * Get store_id
     * @return string|null
     */
    public function getStoreId()
    {
        return $this->_get(self::STORE_ID);
    }

    /**
     * Set store_id
     * @param string $storeId
     * @return \Algolia\Storelocator\Api\Data\StorelocatorInterface
     */
    public function setStoreId($storeId)
    {
        return $this->setData(self::STORE_ID, $storeId);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Algolia\Storelocator\Api\Data\StorelocatorExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Algolia\Storelocator\Api\Data\StorelocatorExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Algolia\Storelocator\Api\Data\StorelocatorExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
