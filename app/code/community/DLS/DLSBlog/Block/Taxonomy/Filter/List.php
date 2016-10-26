<?php

class DLS_DLSBlog_Block_Taxonomy_Filter_List extends DLS_DLSBlog_Block_Filter_List {

    public function __construct() {
        parent::__construct();
        $taxonomy = $this->getTaxonomy();
        if ($taxonomy) {
            $this->getFilters()->addFieldToFilter('taxonomy_id', $taxonomy->getId());
        }
    }

    protected function _prepareLayout() {
        return $this;
    }

    public function getTaxonomy() {
        return Mage::registry('current_taxonomy');
    }

}
