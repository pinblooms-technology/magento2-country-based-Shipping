<?php

namespace PinBlooms\CountryBasedShipping\Model\ResourceModel\Group;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use PinBlooms\CountryBasedShipping\Model\Group as GroupModel;
use PinBlooms\CountryBasedShipping\Model\ResourceModel\Group as GroupResourceModel;

class Collection extends AbstractCollection
{
    /**
     * Cinstructor function
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            GroupModel::class,
            GroupResourceModel::class
        );
    }
}
