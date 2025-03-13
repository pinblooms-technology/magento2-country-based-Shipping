<?php

namespace PinBlooms\CountryBasedShipping\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use PinBlooms\CountryBasedShipping\Model\Group;
use Magento\Backend\Model\Session;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var Group
     */
    protected $Custommodel;

    /**
     * @var Session
     */
    protected $adminsession;

    /**
     * Save Constructor
     *
     * @param Action\Context $context
     * @param Group $Custommodel
     * @param Session $adminsession
     */
    public function __construct(
        Action\Context $context,
        Group $Custommodel,
        Session $adminsession
    ) {
        parent::__construct($context);
        $this->Custommodel = $Custommodel;
        $this->adminsession = $adminsession;
    }

    /**
     * Execute function
     *
     * @return void
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $group_id = $this->getRequest()->getParam('group_id');

            if ($group_id) {
                $this->Custommodel->load($group_id);
            }

            $this->Custommodel->setData($data);

            try {
                $this->Custommodel->save();
                $this->messageManager->addSuccess(__('The data has been saved.'));
                $this->adminsession->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    if ($this->getRequest()->getParam('back') == 'add') {
                        return $resultRedirect->setPath('*/*/add');
                    } else {
                        return $resultRedirect->setPath(
                            '*/*/edit',
                            [
                                'group_id' => $this->Custommodel->getGroupId(),
                                '_current' => true
                            ]
                        );
                    }
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the data.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['group_id' => $this->getRequest()->getParam('group_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
