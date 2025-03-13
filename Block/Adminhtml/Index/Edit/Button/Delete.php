<?php

/**
 * Copyright © PinBlooms. All rights reserved.
 * @license
 */

namespace PinBlooms\CountryBasedShipping\Block\Adminhtml\Index\Edit\Button;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class Delete extends Generic implements ButtonProviderInterface
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * Delete function
     *
     * @param Context $context
     */
    public function __construct(
        Context $context
    ) {
        $this->context = $context;
    }

    /**
     * Getbutton data
     *
     * @return array
     */
    public function getButtonData()
    {
        $data = [];
        $group_id = $this->context->getRequest()->getParam('group_id');
        if ($group_id) {
            $data = [
                'label' => __('Delete'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\'' . __(
                    'Are you sure you want to do this?'
                ) . '\', \'' . $this->getDeleteUrl() . '\')',
                'sort_order' => 20,
            ];
        }
        return $data;
    }

    /**
     * GetDeleteUrl Function
     *
     * @return string
     */
    public function getDeleteUrl()
    {
        $group_id = $this->context->getRequest()->getParam('group_id');
        return $this->getUrl('*/*/delete', ['group_id' => $group_id]);
    }
}
