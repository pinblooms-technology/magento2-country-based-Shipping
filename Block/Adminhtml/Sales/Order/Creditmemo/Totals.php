<?php

namespace PinBlooms\CountryBasedShipping\Block\Adminhtml\Sales\Order\Creditmemo;

class Totals extends \Magento\Framework\View\Element\Template
{
    /**
     * Order invoice
     *
     * @var \Magento\Sales\Model\Order\Creditmemo|null
     */
    protected $_creditmemo = null;

    /**
     * @var \Magento\Framework\DataObject
     */
    protected $_source;

    /**
     * @var \PinBlooms\CountryBasedShipping\Helper\Data
     */
    protected $_dataHelper;

    /**
     * OrderFee function
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \PinBlooms\CountryBasedShipping\Helper\Data $dataHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \PinBlooms\CountryBasedShipping\Helper\Data $dataHelper,
        array $data = []
    ) {
        $this->_dataHelper = $dataHelper;
        parent::__construct($context, $data);
    }

    /**
     * Get data (totals) source model
     *
     * @return \Magento\Framework\DataObject
     */
    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }

    /**
     * GetCreditmemo function
     *
     * @return void
     */
    public function getCreditmemo()
    {
        return $this->getParentBlock()->getCreditmemo();
    }
    /**
     * Initialize payment fee totals
     *
     * @return $this
     */
    public function initTotals()
    {
        $parent = $this->getParentBlock();
        $this->getCreditmemo();
        $this->order = $parent->getOrder();
        $this->source = $parent->getSource();

        $shippingCountry = $this->order->getShippingAddress()
            ->getData()['country_id'];
        $regionId = $this->order->getShippingAddress()
            ->getData()['region_id'];
        $customfee = $this->_dataHelper->getExtrafee($shippingCountry, $regionId);

        $fee = new \Magento\Framework\DataObject(
            [
                'code' => 'fee',
                'strong' => false,
                'value' => $customfee,
                'label' => $this->_dataHelper->getFeeLabel($shippingCountry, $regionId),
            ]
        );

        $this->getParentBlock()->addTotalBefore($fee, 'grand_total');

        return $this;
    }
}
