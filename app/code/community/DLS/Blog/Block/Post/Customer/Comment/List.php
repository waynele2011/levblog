<?php

class DLS_Blog_Block_Post_Customer_Comment_List extends Mage_Customer_Block_Account_Dashboard {

    protected $_collection;

    protected function _construct() {
        $this->_collection = Mage::getResourceModel(
                        'dls_blog/post_comment_post_collection'
        );
        $this->_collection
                ->setStoreFilter(Mage::app()->getStore()->getId(), true)
                ->addAttributeToFilter('status', 1) //only active
                ->addStatusFilter(DLS_Blog_Model_Post_Comment::STATUS_APPROVED) //only approved comments
                ->addCustomerFilter(Mage::getSingleton('customer/session')->getCustomerId()) //only my comments
                ->setDateOrder();
    }

    public function count() {
        return $this->_collection->getSize();
    }

    public function getToolbarHtml() {
        return $this->getChildHtml('toolbar');
    }

    protected function _prepareLayout() {
        $toolbar = $this->getLayout()->createBlock('page/html_pager', 'customer_post_comments.toolbar')
                ->setCollection($this->getCollection());

        $this->setChild('toolbar', $toolbar);
        return parent::_prepareLayout();
    }

    protected function _getCollection() {
        return $this->_collection;
    }

    public function getCollection() {
        return $this->_getCollection();
    }

    public function getCommentLink($comment) {
        if ($comment instanceof Varien_Object) {
            $comment = $comment->getCtCommentId();
        }
        return Mage::getUrl(
                        'dls_blog/post_customer_comment/view/', array('id' => $comment)
        );
    }

    public function getPostLink($comment) {
        return $comment->getPostUrl();
    }

    public function dateFormat($date) {
        return $this->formatDate($date, Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
    }

}
