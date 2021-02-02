<?php
/**
 * Copyright © 2021 Neosoft Technologies. All rights reserved.
 */
declare(strict_types=1);

namespace Algolia\Storelocator\Controller\Adminhtml\Storelocator;

use Algolia\Storelocator\Model\StorelocatorFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class for Save action for storelocator.
 */
class Save extends \Magento\Backend\App\Action
{
    protected $dataPersistor;
    /**
     * @var StorelocatorFactory
     */
    protected $storelocatorFactory;

    /**
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Context $context,
        DataPersistorInterface $dataPersistor,
        StorelocatorFactory $storelocatorFactory
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->storelocatorFactory = $storelocatorFactory;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $this->storelocatorFactory = $this->storelocatorFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('storelocator_id');

            $model = $this->storelocatorFactory->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Storelocator no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }

            $model->setData($data);
            //echo "<pre>";print_r($model->getData());exit;
            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the Storelocator.'));
                $this->dataPersistor->clear('algolia_storelocator_storelocator');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['storelocator_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage(
                    $e,
                    __('Something went wrong while saving the Storelocator.')
                );
            }

            $this->dataPersistor->set('algolia_storelocator_storelocator', $data);
            return $resultRedirect->setPath(
                '*/*/edit',
                ['storelocator_id' => $this->getRequest()->getParam('storelocator_id')]
            );
        }
        return $resultRedirect->setPath('*/*/');
    }
}
