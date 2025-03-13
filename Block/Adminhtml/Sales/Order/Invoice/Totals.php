<?php

namespace PinBlooms\CountryBasedShipping\Block\Adminhtml\Sales\Order\Invoice;

class Totals extends \Magento\Framework\View\Element\Template
{

    /**
     * @var \PinBlooms\CountryBasedShipping\Helper\Data
     */
    protected $_dataHelper;

    /**
     * Order invoice
     *
     * @var \Magento\Sales\Model\Order\Invoice|null
     */
    protected $_invoice = null;

    /**
     * @var \Magento\Framework\DataObject
     */
    protected $_source;

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
     * GetInvoice function
     *
     * @return void
     */
    public function getInvoice()
    {
        return $this->getParentBlock()->getInvoice();
    }

    /**
     * Initialize payment fee totals
     *
     * @return $this
     */
    public function initTotals()
    {
        $parent = $this->getParentBlock();
        $this->getInvoice();
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
