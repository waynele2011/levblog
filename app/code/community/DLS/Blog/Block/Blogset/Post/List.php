<?php

class DLS_Blog_Block_Blogset_Post_List extends DLS_Blog_Block_Post_List {

    public function __construct() {
        parent::__construct();
        $blogset = $this->getBlogset();
        if ($blogset) {
            $collection = $this->getPosts()->addFieldToFilter('blogset_id', $blogset->getId());
            $this->setCollection($collection);
        }
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

    public function getPagerHtml() {
        return $this->getChildHtml('pager');
    }

}
