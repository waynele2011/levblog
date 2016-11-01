<?php

class DLS_Blog_Block_Taxonomy_Children extends DLS_Blog_Block_Taxonomy_List {

    protected function _prepareLayout() {
        $this->getTaxonomies()->addFieldToFilter('parent_id', $this->getCurrentTaxonomy()->getId());
        return $this;
    }

    public function getCurrentTaxonomy() {
        return Mage::registry('current_taxonomy');
    }

}
