<?php

class DLS_Blog_Model_Taxonomy_Filter extends Mage_Core_Model_Abstract {

    protected function _construct() {
        $this->_init('dls_blog/taxonomy_filter');
    }

    public function saveTaxonomyRelation($taxonomy) {
        $data = $taxonomy->getFiltersData();
        if (!is_null($data)) {
            $this->_getResource()->saveTaxonomyRelation($taxonomy, $data);
        }
        return $this;
    }

    public function getFiltersCollection($taxonomy) {
        $collection = Mage::getResourceModel('dls_blog/taxonomy_filter_collection')
                ->addTaxonomyFilter($taxonomy);
        return $collection;
    }

}
