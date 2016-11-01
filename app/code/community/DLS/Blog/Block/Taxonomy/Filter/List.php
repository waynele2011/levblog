<?php

class DLS_Blog_Block_Taxonomy_Filter_List extends DLS_Blog_Block_Filter_List {

    public function __construct() {
        parent::__construct();
        $taxonomy = $this->getTaxonomy();
        if ($taxonomy) {
            $this->getFilters()->addTaxonomyFilter($taxonomy->getId());
            $this->getFilters()->unshiftOrder('related_taxonomy.position', 'ASC');
        }
    }

    protected function _prepareLayout() {
        return $this;
    }

    public function getTaxonomy() {
        return Mage::registry('current_taxonomy');
    }

}
