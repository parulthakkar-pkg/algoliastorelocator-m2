<?php
/**
 * Copyright © 2021 Neosoft Technologies. All rights reserved.
 */
declare(strict_types=1);

namespace Algolia\Storelocator\Model;

class StorelocatorManagement implements \Algolia\Storelocator\Api\StorelocatorManagementInterface
{

    /**
     * {@inheritdoc}
     */
    public function getStorelocator($param)
    {
        return 'hello api GET return the $param ' . $param;
    }
}
