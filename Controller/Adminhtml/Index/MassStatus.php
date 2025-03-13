<?php

namespace PinBlooms\CountryBasedShipping\Controller\Adminhtml\Index;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use PinBlooms\CountryBasedShipping\Model\ResourceModel\Group\CollectionFactory;
use PinBlooms\CountryBasedShipping\Model\Group;

class MassStatus extends \Magento\Backend\App\Action
{
    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var Group
     */
    protected $Groupmodel;

    /**
     * MassStatus Constructor
     *
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param Group $Groupmodel
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        Group $Groupmodel
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->Groupmodel = $Groupmodel;
        parent::__construct($context);
    }

    /**
     * Execute function
     *
     * @return void
     */
    public function execute()
    {
        $groupData = $this->collectionFactory->create();

        foreach ($groupData as $value) {
            $templateId[] = $value['group_id'];
        }
        $parameterData = $this->getRequest()->getParams('status');
        $selectedAppsid = $this->getRequest()->getParams('status');
        if (array_key_exists("selected", $parameterData)) {
            $selectedAppsid = $parameterData['selected'];
        }
        if (array_key_exists("excluded", $parameterData)) {
            if ($parameterData['excluded'] == 'false') {
                $selectedAppsid = $templateId;
            } else {
                $selectedAppsid = array_diff($templateId, $parameterData['excluded']);
            }
        }
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('group_id', ['in' => $selectedAppsid]);
        $status = 0;
        $model = [];
        foreach ($collection as $item) {
            $this->setStatus($item->getGroupId(), $this->getRequest()->getParam('status'));
            $status++;
        }
        $this->messageManager->addSuccess(__('A total of %1 record(s) were updated.', $status));
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Set Status function
     *
     * @param int $id
     * @param string $Param
     * @return void
     */
    public function setStatus($id, $Param)
    {
        $item = $this->Groupmodel->load($id);
        $item->setStatus($Param)->save();
        return;
    }
}
