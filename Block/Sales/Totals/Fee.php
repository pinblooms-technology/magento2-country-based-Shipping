<?php

namespace PinBlooms\CountryBasedShipping\Block\Sales\Totals;

class Fee extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \PinBlooms\CountryBasedShipping\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var Order
     */
    protected $order;

    /**
     * @var \Magento\Framework\DataObject
     */
    protected $source;

    /**
     * Fee function
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
        $this->dataHelper = $dataHelper;
        parent::__construct($context, $data);
    }

    /**
     * Get data (totals) source model
     *
     * @return \Magento\Framework\DataObject
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * GetOrder function
     *
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * InitTotals function
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
        $customfee = $this->dataHelper->getExtrafee($shippingCountry, $regionId);

        $fee = new \Magento\Framework\DataObject(
            [
                'code' => 'fee',
                'strong' => false,
                'value' => $customfee,
                'label' => $this->dataHelper->getFeeLabel($shippingCountry, $regionId),
            ]
        );

        $parent->addTotal($fee, 'fee');

        return $this;
    }
}
