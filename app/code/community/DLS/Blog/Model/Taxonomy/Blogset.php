<?php

class DLS_Blog_Model_Taxonomy_Blogset extends Mage_Core_Model_Abstract {

    protected function _construct() {
        $this->_init('dls_blog/taxonomy_blogset');
    }

    public function saveTaxonomyRelation($taxonomy) {
        $data = $taxonomy->getBlogsetsData();
        if (!is_null($data)) {
            $this->_getResource()->saveTaxonomyRelation($taxonomy, $data);
        }
        return $this;
    }

    public function getBlogsetsCollection($taxonomy) {
        $collection = Mage::getResourceModel('dls_blog/taxonomy_blogset_collection')
                ->addTaxonomyFilter($taxonomy);
        return $collection;
    }

}
