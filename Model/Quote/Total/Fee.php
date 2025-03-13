<?php

namespace PinBlooms\CountryBasedShipping\Model\Quote\Total;

use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address;
use Magento\Quote\Model\Quote\Address\Total;

class Fee extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{

    /**
     * @var \PinBlooms\CountryBasedShipping\Helper\Data
     */
    protected $helperData;

    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    protected $priceCurrency;

    /**
     * Undocumented variable
     *
     * @var \Magento\Checkout\Model\Cart
     */
    protected $_checkoutCart;

    /**
     * @var \Magento\Quote\Model\QuoteValidator
     */
    protected $quoteValidator = null;

    /**
     * Fee Constructor
     *
     * @param \Magento\Quote\Model\QuoteValidator $quoteValidator
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     * @param \PinBlooms\CountryBasedShipping\Helper\Data $helperData
     * @param \Magento\Checkout\Model\Cart $checkoutCart
     */
    public function __construct(
        \Magento\Quote\Model\QuoteValidator $quoteValidator,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \PinBlooms\CountryBasedShipping\Helper\Data $helperData,
        \Magento\Checkout\Model\Cart $checkoutCart
    ) {
        $this->quoteValidator = $quoteValidator;
        $this->priceCurrency = $priceCurrency;
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
            }
            if (!$regionId) {
                $regionId = $this->_checkoutCart->getQuote()
                    ->getShippingAddress()->getRegionId();
            }
            $fee = $this->helperData->getExtrafee($shippingCountry, $regionId);

            $total->setTotalAmount('fee', $fee);
            $total->setBaseTotalAmount('fee', $fee);
            $total->setFee($fee);
            $total->setBaseFee($fee);

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $productMetadata = $objectManager->get(\Magento\Framework\App\ProductMetadataInterface::class);
            $version = (float)$productMetadata->getVersion();

            if ($version > 2.1) {
                //$total->setGrandTotal($total->getGrandTotal() + $fee);
            } else {
                $total->setGrandTotal($total->getGrandTotal() + $fee);
                $total->setBaseGrandTotal($total->getBaseGrandTotal() + $fee);
            }
        }
        return $this;
    }

    /**
     * Fetch function
     *
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return array
     */
    public function fetch(\Magento\Quote\Model\Quote $quote, \Magento\Quote\Model\Quote\Address\Total $total)
    {

        $enabled = 1;
        $address = $this->_getAddressFromQuote($quote);
        $shippingCountry = $address->getData()['country_id'];
        $regionId = $address->getData()['region_id'];
        if (!$shippingCountry) {
            $shippingCountry = $this->_checkoutCart->getQuote()
                ->getShippingAddress()->getCountryId();
        }
        if (!$regionId) {
            $regionId = $this->_checkoutCart->getQuote()
                ->getShippingAddress()->getRegionId();
        }
        $label = $this->helperData->getFeeLabel($shippingCountry, $regionId);
        $fee = $this->helperData->getExtrafee($shippingCountry, $regionId);

        $result = [];
        if ($enabled && $fee) {
            $result = [
                'code' => 'fee',
                'title' => $label,
                'value' => $fee
            ];
        }

        return $result;
    }

    /**
     * Get Subtotal label
     *
     * @return \Magento\Framework\Phrase
     */
    public function getLabel()
    {
        return __('Extra Fee');
    }

    /**
     * GetAddressFromQuote function
     *
     * @param Quote $quote
     * @return void
     */
    protected function _getAddressFromQuote(Quote $quote)
    {
        return $quote->isVirtual() ? $quote->getBillingAddress() : $quote->getShippingAddress();
    }
}
