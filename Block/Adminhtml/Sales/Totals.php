<?php

namespace PinBlooms\CountryBasedShipping\Block\Adminhtml\Sales;

class Totals extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \PinBlooms\CountryBasedShipping\Helper\Data
     */
    protected $_dataHelper;

    /**
     * @var \Magento\Directory\Model\Currency
     */
    protected $_currency;

    /**
     * Totals function
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \PinBlooms\CountryBasedShipping\Helper\Data $dataHelper
     * @param \Magento\Directory\Model\Currency $currency
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \PinBlooms\CountryBasedShipping\Helper\Data $dataHelper,
        \Magento\Directory\Model\Currency $currency,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_dataHelper = $dataHelper;
        $this->_currency = $currency;
    }

    /**
     * Retrieve current order model instance
     *
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder()
    {
        return $this->getParentBlock()->getOrder();
    }

    /**
     * GetSource function
     *
     * @return mixed
     */
    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }

    /**
     * GetCurrencySymbol Function
     *
     * @return string
     */
    public function getCurrencySymbol()
    {
        return $this->_currency->getCurrencySymbol();
    }

    /**
     * InitTotals Function
     *
     * @return $this
     */
    public function initTotals()
    {
        $parent = $this->getParentBlock();
        $this->order = $parent->getOrder();
        $this->source = $parent->getSource();

        $shippingCountry = $this->order->getShippingAddress()
            ->getData()['country_id'];
        $regionId = $this->order->getShippingAddress()
            ->getData()['region_id'];
        $customfee = $this->_dataHelper->getExtrafee($shippingCountry, $regionId);

        $total = new \Magento\Framework\DataObject(
            [
                'code' => 'fee',
                'value' => $customfee,
                'label' => $this->_dataHelper->getFeeLabel($shippingCountry, $regionId),
            ]
        );
        $this->getParentBlock()->addTotalBefore($total, 'grand_total');

        return $this;
    }
}
