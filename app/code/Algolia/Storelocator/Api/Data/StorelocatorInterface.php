<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Algolia\Storelocator\Api\Data;

interface StorelocatorInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const STORE_ID = 'store_id';
    const STORELOCATOR_ID = 'storelocator_id';

    /**
     * Get storelocator_id
     * @return string|null
     */
    public function getStorelocatorId();

    /**
     * Set storelocator_id
     * @param string $storelocatorId
     * @return \Algolia\Storelocator\Api\Data\StorelocatorInterface
     */
    public function setStorelocatorId($storelocatorId);

    /**
     * Get store_id
     * @return string|null
     */
    public function getStoreId();

    /**
     * Set store_id
     * @param string $storeId
     * @return \Algolia\Storelocator\Api\Data\StorelocatorInterface
     */
    public function setStoreId($storeId);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Algolia\Storelocator\Api\Data\StorelocatorExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Algolia\Storelocator\Api\Data\StorelocatorExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Algolia\Storelocator\Api\Data\StorelocatorExtensionInterface $extensionAttributes
    );
}

