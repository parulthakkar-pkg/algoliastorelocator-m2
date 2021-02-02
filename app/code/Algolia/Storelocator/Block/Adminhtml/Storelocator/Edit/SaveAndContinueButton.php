<?php
/**
 * Copyright © 2021 Neosoft Technologies. All rights reserved.
 */
declare(strict_types=1);
namespace Algolia\Storelocator\Block\Adminhtml\Storelocator\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class for Save and Continue Button.
 */
class SaveAndContinueButton extends GenericButton implements ButtonProviderInterface
{

    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Save and Continue Edit'),
            'class' => 'save',
            'data_attribute' => [
                'mage-init' => [
                    'button' => ['event' => 'saveAndContinueEdit'],
                ],
            ],
            'sort_order' => 80,
        ];
    }
}
