<?php
/**
 * Copyright © 2021 Neosoft Technologies. All rights reserved.
 */
namespace Algolia\Storelocator\Test\Unit\Helper;

use Algolia\AlgoliaSearch\Helper\AlgoliaHelper;
use Algolia\AlgoliaSearch\Helper\ConfigHelper;
use Algolia\AlgoliaSearch\Helper\Data;
use Algolia\AlgoliaSearch\Helper\Entity\AdditionalSectionHelper;
use Algolia\AlgoliaSearch\Helper\Entity\CategoryHelper;
use Algolia\AlgoliaSearch\Helper\Entity\PageHelper;
use Algolia\AlgoliaSearch\Helper\Entity\ProductHelper;
use Algolia\AlgoliaSearch\Helper\Entity\SuggestionHelper;
use Algolia\AlgoliaSearch\Helper\Logger;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Framework\App\Config\ScopeCodeResolver;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Event\ManagerInterface;
use Magento\Store\Model\App\Emulation;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class for unitclass for storelocator.
 */
class DataTest extends \PHPUnit\Framework\TestCase
{
    protected $indexName = 'magento2_localdefault_storeslocators';
    /**
     * @var Data|MockObject
     */
    protected $dataMock;

    /**
     * @return void
     */
    protected function setUp() : void
    {
        $this->dataMock = $this->createMock(Data::class);
        $this->algoliaHelperMock = $this->createMock(AlgoliaHelper::class);

        $this->pageHelperMock = $this->createMock(PageHelper::class);
        $this->categoryHelperMock = $this->createMock(CategoryHelper::class);
        $this->productHelperMock = $this->createMock(ProductHelper::class);
        $this->suggestionHelperMock = $this->createMock(SuggestionHelper::class);
        $this->additionalSectionHelperMock = $this->createMock(AdditionalSectionHelper::class);
        $this->stockRegistryMock = $this->createMock(StockRegistryInterface::class);

        $this->configHelperMock = $this->createMock(ConfigHelper::class);
        $this->loggerMock = $this->createMock(Logger::class);
        $this->emulationMock = $this->createMock(Emulation::class);
        $this->resourceMock = $this->createMock(ResourceConnection::class);
        $this->eventManagerMock = $this->createMock(ManagerInterface::class);
        $this->scopeCodeResolverMock = $this->createMock(ScopeCodeResolver::class);
        $this->storeManagerMock = $this->createMock(StoreManagerInterface::class);
        parent::setUp();
    }
    public function testrebuildStoreLocatorIndex()
    {
        $resultStub = $this->getSut()->getIndexName('_storelocators', 'default');
        $this->assertEquals($this->indexName, $resultStub);
    }
    /**
     * @return PagerPlugin
     */
    private function getSut(): Data
    {
        return new Data(
            $this->algoliaHelperMock,
            $this->categoryHelperMock,
            $this->pageHelperMock,
            $this->productHelperMock,
            $this->suggestionHelperMock,
            $this->additionalSectionHelperMock,
            $this->stockRegistryMock,
            $this->configHelperMock,
            $this->loggerMock,
            $this->emulationMock,
            $this->resourceMock,
            $this->eventManagerMock,
            $this->scopeCodeResolverMock,
            $this->storeManagerMock
        );
    }
}
