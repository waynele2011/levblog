<?php

class DLS_DLSBlog_Block_Taxonomy_Post_List extends DLS_DLSBlog_Block_Post_List {

    public function __construct() {
        parent::__construct();
        $taxonomy = $this->getTaxonomy();
        if ($taxonomy) {
            $collection = $this->getPosts()->addFieldToFilter('taxonomy_id', $taxonomy->getId());
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

    public function getTaxonomy() {
        return Mage::registry('current_taxonomy');
    }
    
    public function getPagerHtml() {
        return $this->getChildHtml('pager');
    }

}
