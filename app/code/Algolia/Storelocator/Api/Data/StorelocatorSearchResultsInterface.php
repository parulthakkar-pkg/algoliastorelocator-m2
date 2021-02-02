<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Algolia\Storelocator\Api\Data;

interface StorelocatorSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get storelocator list.
     * @return \Algolia\Storelocator\Api\Data\StorelocatorInterface[]
     */
    public function getItems();

    /**
     * Set store_id list.
     * @param \Algolia\Storelocator\Api\Data\StorelocatorInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

