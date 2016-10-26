<?php

class DLS_DLSBlog_Block_Filter_Post_View extends DLS_DLSBlog_Block_Post_List {

    public function __construct() {
        parent::__construct();
        $filter = $this->getFilter();
        if ($filter) {
            $collection = $this->getPosts()->addFilterFilter($filter->getId());
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

    public function getFilter() {
        return Mage::registry('current_filter');
    }

    public function getPagerHtml() {
        return $this->getChildHtml('pager');
    }

}
