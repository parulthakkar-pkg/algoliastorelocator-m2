<?php
/**
 * Copyright © 2021 Neosoft Technologies. All rights reserved.
 */
declare(strict_types=1);

namespace Algolia\Storelocator\Controller\Adminhtml\Storelocator;

use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;

/**
 * Class for Delete Button.
 */
class Delete extends \Algolia\Storelocator\Controller\Adminhtml\Storelocator
{

    /**
     * Delete action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('storelocator_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create(\Algolia\Storelocator\Model\Storelocator::class);
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the Storelocator.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['storelocator_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Storelocator to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
