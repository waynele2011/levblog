<?php

class DLS_Blog_Model_Taxonomy_Post extends Mage_Core_Model_Abstract {

    protected function _construct() {
        $this->_init('dls_blog/taxonomy_post');
    }

    public function saveTaxonomyRelation($taxonomy) {
        $data = $taxonomy->getPostsData();
        if (!is_null($data)) {
            $this->_getResource()->saveTaxonomyRelation($taxonomy, $data);
        }
        return $this;
    }

    public function getPostsCollection($taxonomy) {
        $collection = Mage::getResourceModel('dls_blog/taxonomy_post_collection')
                ->addTaxonomyFilter($taxonomy);
        return $collection;
    }

}
