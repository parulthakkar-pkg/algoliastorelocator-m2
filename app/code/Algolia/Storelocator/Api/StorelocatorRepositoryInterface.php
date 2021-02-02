<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Algolia\Storelocator\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface StorelocatorRepositoryInterface
{

    /**
     * Save storelocator
     * @param \Algolia\Storelocator\Api\Data\StorelocatorInterface $storelocator
     * @return \Algolia\Storelocator\Api\Data\StorelocatorInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Algolia\Storelocator\Api\Data\StorelocatorInterface $storelocator
    );

    /**
     * Retrieve storelocator
     * @param string $storelocatorId
     * @return \Algolia\Storelocator\Api\Data\StorelocatorInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($storelocatorId);

    /**
     * Retrieve storelocator matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Algolia\Storelocator\Api\Data\StorelocatorSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete storelocator
     * @param \Algolia\Storelocator\Api\Data\StorelocatorInterface $storelocator
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Algolia\Storelocator\Api\Data\StorelocatorInterface $storelocator
    );

    /**
     * Delete storelocator by ID
     * @param string $storelocatorId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($storelocatorId);
}

