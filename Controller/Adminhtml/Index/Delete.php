<?php

/**
 * Copyright © PinBlooms. All rights reserved.
 * @license
 */

namespace PinBlooms\CountryBasedShipping\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;

class Delete extends Action
{
    /**
     * @var \PinBlooms\CountryBasedShipping\Model\Group
     */
    protected $modelGroup;

    /**
     * @param Action\Context $context
     * @param \PinBlooms\CountryBasedShipping\Model\Group $model
     */
    public function __construct(
        Action\Context $context,
        \PinBlooms\CountryBasedShipping\Model\Group $model
    ) {
        parent::__construct($context);
        $this->modelGroup = $model;
    }

    /**
     * Is Allowed function
     *
     * @return void
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('PinBlooms_CountryBasedShipping::index_delete');
    }

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('group_id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $model = $this->modelGroup;
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccess(__('Record deleted'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        $this->messageManager->addError(__('Record does not exist'));
        return $resultRedirect->setPath('*/*/');
    }
}
