<?php

class DLS_DLSBlog_Block_Taxonomy_Children extends DLS_DLSBlog_Block_Taxonomy_List {

    protected function _prepareLayout() {
        $this->getTaxonomies()->addFieldToFilter('parent_id', $this->getCurrentTaxonomy()->getId());
        return $this;
    }

    public function getCurrentTaxonomy() {
        return Mage::registry('current_taxonomy');
    }

}
