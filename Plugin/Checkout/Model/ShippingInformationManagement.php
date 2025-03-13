<?php

namespace PinBlooms\CountryBasedShipping\Plugin\Checkout\Model;

class ShippingInformationManagement
{
    /**
     * @var \Magento\Quote\Model\QuoteRepository
     */
    protected $quoteRepository;

    /**
     * @var \PinBlooms\CountryBasedShipping\Helper\Data
     */
    protected $dataHelper;

    /**
     * @param \Magento\Quote\Model\QuoteRepository $quoteRepository
     * @param \PinBlooms\CountryBasedShipping\Helper\Data $dataHelper
     */
    public function __construct(
        \Magento\Quote\Model\QuoteRepository $quoteRepository,
        \PinBlooms\CountryBasedShipping\Helper\Data $dataHelper
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->dataHelper = $dataHelper;
    }

    /**
     * ShippingInformationManagement function
     *
     * @param \Magento\Checkout\Model\ShippingInformationManagement $subject
     * @param int $cartId
     * @param \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
     * @return void
     */
    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        $cartId,
        \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
    ) {
        $Extrafee = $addressInformation->getExtensionAttributes()->getFee();
        $quote = $this->quoteRepository->getActive($cartId);
        $shippingCountry = $addressInformation->getShippingAddress()->getCountryId();
        $regionId = $addressInformation->getShippingAddress()->getRegionId();
        if ($Extrafee) {
            $fee = $this->dataHelper->getExtrafee($shippingCountry, $regionId);
            $quote->setFee($fee);
        } else {
            $quote->setFee(null);
        }
    }
}
