<?php

namespace PinBlooms\CountryBasedShipping\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Group extends AbstractDb
{
    /**
     * Constructor function
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('pinblooms_country_group', 'group_id');
    }
}
