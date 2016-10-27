<?php

/**
 * Post comment model
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Model_Post_Comment extends Mage_Core_Model_Abstract
{
    const STATUS_PENDING  = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = 2;
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'dls_dlsblog_post_comment';
    const CACHE_TAG = 'dls_dlsblog_post_comment';
    /**
     * Prefix of model events names
     * @var string
     */
    protected $_eventPrefix = 'dls_dlsblog_post_comment';

    /**
     * Parameter name in event
     * @var string
     */
    protected $_eventObject = 'comment';

    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('dls_dlsblog/post_comment');
    }

    /**
     * before save post comment
     *
     * @access protected
     * @return DLS_DLSBlog_Model_Post_Comment
     * @author Ultimate Module Creator
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }

    /**
     * validate comment
     *
     * @access public
     * @return array|bool
     * @author Ultimate Module Creator
     */
    public function validate()
    {
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
