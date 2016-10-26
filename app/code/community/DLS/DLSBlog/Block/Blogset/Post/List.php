<?php

class DLS_DLSBlog_Block_Blogset_Post_List extends Mage_Core_Block_Template {

    public function __construct() {
        parent::__construct();
        $blogset = $this->getBlogset();
        // @TODO
        $collection = Mage::getModel('dls_dlsblog/post')->getPostByFilter(1);
        $this->setCollection($collection);
    }

    protected function _prepareLayout() {
        $pager = $this->getLayout()->createBlock('page/html_pager', 'list.pager');
        $pager->setAvailableLimit(array(5 => 5, 10 => 10, 20 => 20, 'all' => 'all'));
        $pager->setCollection($this->getCollection());
        $this->setChild('pager', $pager);
        $this->getCollection()->load();
        return $this;
    }

    public function getBlogset() {
        return Mage::registry('current_blogset');
    }

    public function getPostContent() {
        return Mage::getModel('dls_dlsblog/post')->getPostByFilter(1);
    }

    public function getPagerHtml() {
        return $this->getChildHtml('pager');
    }

}
