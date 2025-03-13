<?php

namespace PinBlooms\CountryBasedShipping\Model\Sales\Pdf;

class Fee extends \Magento\Sales\Model\Order\Pdf\Total\DefaultTotal
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
     * Fee Construtor
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
     * GetTotalsForDisplay function
     *
     * @return void
     */
    public function getTotalsForDisplay()
    {
        $shippingCountry = $this->getOrder()->getShippingAddress()
            ->getData()['country_id'];
        $regionId = $this->getOrder()->getShippingAddress()
            ->getData()['region_id'];
        $fee = $this->_dataHelper->getExtrafee($shippingCountry, $regionId);
        $label = $this->_dataHelper->getFeeLabel($shippingCountry, $regionId);
        $amount = $this->getOrder()->formatPriceTxt($fee);

        $amountInclTax = $this->getOrder()->formatPriceTxt($fee);
        $fontSize = $this->getFontSize() ? $this->getFontSize() : 7;

        return [
            [
                'amount' => $this->getAmountPrefix() . $amountInclTax,
                'label' => __($label) . ':',
                'font_size' => $fontSize,
            ]
        ];
    }
}
