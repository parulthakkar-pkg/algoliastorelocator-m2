<?php
/**
 * Copyright © 2021 Neosoft Technologies. All rights reserved.
 */
declare(strict_types=1);

namespace Algolia\Storelocator\Controller\Adminhtml\Storelocator;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory as PageFactoryAlias;

/**
 * Class for Index page.
 */
class Index extends \Magento\Backend\App\Action
{
    protected $resultPageFactory;

    /**
     * Constructor
     *
     * @param Context $context
     * @param PageFactoryAlias $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactoryAlias $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * Index action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__("storelocator"));
        return $resultPage;
    }
}
