<?php

namespace PinBlooms\CountryBasedShipping\Model;

use Magento\Framework\Model\AbstractModel;
use PinBlooms\CountryBasedShipping\Model\ResourceModel\Group as GroupResourceModel;

class Group extends AbstractModel
{
    /**
     * Constructor function
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(GroupResourceModel::class);
    }
}
