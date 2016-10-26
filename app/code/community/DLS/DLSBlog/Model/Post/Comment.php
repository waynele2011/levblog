<?php

class DLS_DLSBlog_Model_Post_Comment extends Mage_Core_Model_Abstract {

    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = 2;
    const ENTITY = 'dls_dlsblog_post_comment';
    const CACHE_TAG = 'dls_dlsblog_post_comment';

    protected $_eventPrefix = 'dls_dlsblog_post_comment';
    protected $_eventObject = 'comment';

    public function _construct() {
        parent::_construct();
        $this->_init('dls_dlsblog/post_comment');
    }

    protected function _beforeSave() {
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }

    public function validate() {
        $errors = array();

        if (!Zend_Validate::is($this->getTitle(), 'NotEmpty')) {
            $errors[] = Mage::helper('review')->__('Comment title can\'t be empty');
        }

        if (!Zend_Validate::is($this->getName(), 'NotEmpty')) {
            $errors[] = Mage::helper('review')->__('Your name can\'t be empty');
        }

        if (!Zend_Validate::is($this->getComment(), 'NotEmpty')) {
            $errors[] = Mage::helper('review')->__('Comment can\'t be empty');
        }

        if (empty($errors)) {
            return true;
        }
        return $errors;
    }

}
