<?php

class DLS_Blog_Block_Taxonomy_Blogset_List extends DLS_Blog_Block_Blogset_List {

    public function __construct() {
        parent::__construct();
        $taxonomy = $this->getTaxonomy();
        if ($taxonomy) {
            $this->getBlogsets()->addTaxonomyFilter($taxonomy->getId());
            $this->getBlogsets()->unshiftOrder('related_taxonomy.position', 'ASC');
        }
    }

    protected function _prepareLayout() {
        return $this;
    }

    public function getTaxonomy() {
        return Mage::registry('current_taxonomy');
    }

}
