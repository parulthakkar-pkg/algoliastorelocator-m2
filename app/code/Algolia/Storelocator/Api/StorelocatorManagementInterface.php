<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Algolia\Storelocator\Api;

interface StorelocatorManagementInterface
{

    /**
     * GET for storelocator api
     * @param string $param
     * @return string
     */
    public function getStorelocator($param);
}

