<?php

class DLS_Blog_Block_Filter_Taxonomy_List extends DLS_Blog_Block_Taxonomy_List {

    public function __construct() {
        parent::__construct();
        $filter = $this->getFilter();
        if ($filter) {
            $this->getTaxonomies()->addFilterFilter($filter->getId());
            $this->getTaxonomies()->unshiftOrder('related_filter.position', 'ASC');
        }
    }

    protected function _prepareLayout() {
        return $this;
    }

    public function getFilter() {
        return Mage::registry('current_filter');
    }

}
