<?php
/**
 * Copyright © 2021 Neosoft Technologies. All rights reserved.
 */
declare(strict_types=1);
namespace Algolia\Storelocator\Block\Adminhtml\Storelocator\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class for save Button.
 */
class SaveButton extends GenericButton implements ButtonProviderInterface
{

    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Save Storelocator'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'sort_order' => 90,
        ];
    }
}
