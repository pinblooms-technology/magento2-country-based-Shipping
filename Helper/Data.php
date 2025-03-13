<?php

namespace PinBlooms\CountryBasedShipping\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    /**
     * @var \PinBlooms\CountryBasedShipping\Model\ResourceModel\Group\CollectionFactory
     */
    protected $feeData;

    /**
     * Data function
     *
     * @param \PinBlooms\CountryBasedShipping\Model\ResourceModel\Group\CollectionFactory $feeData
     */
    public function __construct(
        \PinBlooms\CountryBasedShipping\Model\ResourceModel\Group\CollectionFactory $feeData
    ) {
        $this->feeData = $feeData;
    }

    /**
     * GetCountryFeeData function
     *
     * @return void
     */
    public function getCountryFeeData()
    {
        return $this->feeData->create();
    }

    /**
     * Get custom fee function
     *
     * @param string $shippingCountry
     * @param string $region
     * @return void
     */
    public function getExtrafee($shippingCountry, $region = "")
    {
        $extrafee = 0;
        $countryFeeData = $this->getCountryFeeData()->getData();
        foreach ($countryFeeData as $fee) {
            if ($fee['status'] == 1 && $fee['country_id'] == $shippingCountry && (($fee['region_id'] != "" && $fee['region_id'] == $region) || ($fee['region_id'] == ""))) {
                $extrafee = $fee['amount'];
            }
        }
        return $extrafee;
    }

    /**
     * FetFeeLabel function
     *
     * @param [type] $shippingCountry
     * @param string $region
     * @return void
     */
    public function getFeeLabel($shippingCountry, $region = "")
    {
        $feeLabel = __('Extra Fee');
        $countryFeeData = $this->getCountryFeeData()->getData();
        foreach ($countryFeeData as $fee) {
            if ($fee['status'] == 1 && $fee['country_id'] == $shippingCountry && (($fee['region_id'] != "" && $fee['region_id'] == $region) || ($fee['region_id'] == ""))) {
                $feeLabel = __($fee['name']);
            }
        }
        return $feeLabel;
    }
}
