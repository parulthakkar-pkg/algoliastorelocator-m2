<?php
/**
 * Copyright © 2021 Neosoft Technologies. All rights reserved.
 */
declare(strict_types=1);

namespace Algolia\Storelocator\Controller\Adminhtml\Storelocator;

use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class for Edit Store Information.
 */
class Edit extends \Algolia\Storelocator\Controller\Adminhtml\Storelocator
{

    protected $resultPageFactory;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * Edit action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('storelocator_id');
        $model = $this->_objectManager->create(\Algolia\Storelocator\Model\Storelocator::class);

        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Storelocator no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->_coreRegistry->register('algolia_storelocator_storelocator', $model);

        // 3. Build edit form
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Storelocator') : __('New Storelocator'),
            $id ? __('Edit Storelocator') : __('New Storelocator')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Storelocators'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId()
            ? __('Edit Storelocator %1', $model->getId()) : __('New Storelocator'));
        return $resultPage;
    }
}
