<?php

class DLS_DLSBlog_Block_Post_Comment_Form extends Mage_Core_Block_Template {

    public function __construct() {
        $customerSession = Mage::getSingleton('customer/session');
        parent::__construct();
        $data = Mage::getSingleton('customer/session')->getPostCommentFormData(true);
        $data = new Varien_Object($data);
        // add logged in customer name as nickname
        if (!$data->getName()) {
            $customer = $customerSession->getCustomer();
            if ($customer && $customer->getId()) {
                $data->setName($customer->getFirstname());
                $data->setEmail($customer->getEmail());
            }
        }
        $this->setAllowWriteCommentFlag(
                $customerSession->isLoggedIn() ||
                Mage::getStoreConfigFlag('dls_dlsblog/post/allow_guest_comment')
        );
        if (!$this->getAllowWriteCommentFlag()) {
            $this->setLoginLink(
                    Mage::getUrl(
                            'customer/account/login/', array(
                        Mage_Customer_Helper_Data::REFERER_QUERY_PARAM_NAME => Mage::helper('core')->urlEncode(
                                Mage::getUrl('*/*/*', array('_current' => true)) .
                                '#comment-form'
                        )
                            )
                    )
            );
        }
        $this->setCommentData($data);
    }

    public function getPost() {
        return Mage::registry('current_post');
    }

    public function getAction() {
        return Mage::getUrl(
                        'dls_dlsblog/post/commentpost', array('id' => $this->getPost()->getId())
        );
    }

}
