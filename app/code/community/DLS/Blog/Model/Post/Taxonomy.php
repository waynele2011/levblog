<?php

class DLS_Blog_Model_Post_Taxonomy extends Mage_Core_Model_Abstract {

    protected function _construct() {
        $this->_init('dls_blog/post_taxonomy');
    }

    public function savePostRelation($post) {
        $data = $post->getTaxonomiesData();
        if (!is_null($data)) {
            $this->_getResource()->savePostRelation($post, $data);
        }
        return $this;
    }

    public function getTaxonomiesCollection($post) {
        $collection = Mage::getResourceModel('dls_blog/post_taxonomy_collection')
                ->addPostFilter($post);
        return $collection;
    }

}
