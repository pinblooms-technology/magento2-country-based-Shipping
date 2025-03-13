<?php

namespace PinBlooms\CountryBasedShipping\Model\Creditmemo\Total;

use Magento\Sales\Model\Order\Creditmemo\Total\AbstractTotal;

class Fee extends AbstractTotal
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
     * Fee Constructor
     *
     * @param \PinBlooms\CountryBasedShipping\Helper\Data $dataHelper
     * @param \Magento\Directory\Model\Currency $currency
     */
    public function __construct(
        \PinBlooms\CountryBasedShipping\Helper\Data $dataHelper,
        \Magento\Directory\Model\Currency $currency
    ) {
        $this->_dataHelper = $dataHelper;
        $this->_currency = $currency;
    }

    /**
     * Collect function
     *
     * @param \Magento\Sales\Model\Order\Creditmemo $creditmemo
     * @return $this
     */
    public function collect(\Magento\Sales\Model\Order\Creditmemo $creditmemo)
    {
        $order = $creditmemo->getOrder();
        $shippingCountry = $order->getShippingAddress()
            ->getData()['country_id'];
        $regionId = $order->getShippingAddress()
            ->getData()['region_id'];
        $fee = $this->_dataHelper->getExtrafee($shippingCountry, $regionId);

        $creditmemo->setFee($fee);
        $creditmemo->setBaseFee($fee);

        $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $creditmemo->getFee());
        $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $creditmemo->getFee());

        return $this;
    }
}
