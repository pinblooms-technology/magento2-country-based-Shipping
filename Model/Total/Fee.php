<?php

namespace PinBlooms\CountryBasedShipping\Model\Total;

class Fee extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{
    /**
     * @var \PinBlooms\CountryBasedShipping\Helper\Data
     */
    protected $helperData;

    /**
     * @var \PinBlooms\CountryBasedShipping\Helper\Data
     */
    protected $_checkoutCart;

    /**
     * @var \Magento\Quote\Model\QuoteValidator
     */
    protected $quoteValidator = null;

    /**
     * Fee Contructor
     *
     * @param \Magento\Quote\Model\QuoteValidator $quoteValidator
     * @param \PinBlooms\CountryBasedShipping\Helper\Data $helperData
     * @param \Magento\Checkout\Model\Cart $checkoutCart
     */
    public function __construct(
        \Magento\Quote\Model\QuoteValidator $quoteValidator,
        \PinBlooms\CountryBasedShipping\Helper\Data $helperData,
        \Magento\Checkout\Model\Cart $checkoutCart
    ) {
        $this->quoteValidator = $quoteValidator;
        $this->helperData = $helperData;
        $this->_checkoutCart = $checkoutCart;
    }

    /**
     * Collect function
     *
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return void
     */
    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    ) {

        parent::collect($quote, $shippingAssignment, $total);
        if (!count($shippingAssignment->getItems())) {
            return $this;
        }

        $enabled = 1;
        if ($enabled) {
            $shippingCountry = $quote->getShippingAddress()->getCountry();
            $regionId = $quote->getShippingAddress()->getRegionId();
            if (!$shippingCountry) {
                $shippingCountry = $this->_checkoutCart->getQuote()
                    ->getShippingAddress()->getCountryId();
                $quote->getShippingAddress()->setCountryId($shippingCountry);
            }
            if (!$regionId) {
                $regionId = $this->_checkoutCart->getQuote()
                    ->getShippingAddress()->getRegionId();
                $quote->getShippingAddress()->setRegionId($regionId);
            }
            $fee = $this->helperData->getExtrafee($shippingCountry, $regionId);

            $total->setTotalAmount('fee', $fee);
            $total->setBaseTotalAmount('fee', $fee);
            $total->setFee($fee);
            $total->setBaseFee($fee);
            $total->setGrandTotal($total->getGrandTotal() + $fee);
            $total->setBaseGrandTotal($total->getBaseGrandTotal() + $fee);
        }
        return $this;
    }

    /**
     * Fetch function
     *
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return void
     */
    public function fetch(\Magento\Quote\Model\Quote $quote, \Magento\Quote\Model\Quote\Address\Total $total)
    {
        $enabled = 1;
        $address = $this->_getAddressFromQuote($quote);
        $shippingCountry = $address->getData()['country_id'];
        $regionId = $address->getData()['region_id'];
        if (!$shippingCountry) {
            $shippingCountry = $this->_checkoutCart->getQuote()->getShippingAddress()->getCountryId();
        }
        if (!$regionId) {
            $regionId = $this->_checkoutCart->getQuote()->getShippingAddress()->getRegionId();
        }
        $label = $this->helperData->getFeeLabel($shippingCountry, $regionId);
        $fee = $this->helperData->getExtrafee($shippingCountry, $regionId);

        if ($enabled && $fee) {
            return [
                'code' => 'fee',
                'title' => $label,
                'value' => $fee
            ];
        } else {
            return [];
        }
    }

    /**
     * Get Subtotal label
     *
     * @return \Magento\Framework\Phrase
     */
    public function getLabel()
    {
        return __('Custom Fee');
    }

    /**
     * GetAddressFromQuote function
     *
     * @param array $quote
     * @return void
     */
    protected function _getAddressFromQuote($quote)
    {
        return $quote->isVirtual() ? $quote->getBillingAddress() : $quote->getShippingAddress();
    }
}
