<?php

class DLS_Blog_Model_Filter_Taxonomy extends Mage_Core_Model_Abstract {

    protected function _construct() {
        $this->_init('dls_blog/filter_taxonomy');
    }

    public function saveFilterRelation($filter) {
        $data = $filter->getTaxonomiesData();
        if (!is_null($data)) {
            $this->_getResource()->saveFilterRelation($filter, $data);
        }
        return $this;
    }

    public function getTaxonomiesCollection($filter) {
        $collection = Mage::getResourceModel('dls_blog/filter_taxonomy_collection')
                ->addFilterFilter($filter);
        return $collection;
    }

}
