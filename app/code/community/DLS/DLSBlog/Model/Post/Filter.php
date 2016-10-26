<?php

class DLS_DLSBlog_Model_Post_Filter extends Mage_Core_Model_Abstract {

    protected function _construct() {
        $this->_init('dls_dlsblog/post_filter');
    }

    public function savePostRelation($post) {
        $data = $post->getFiltersData();
        if (!is_null($data)) {
            $this->_getResource()->savePostRelation($post, $data);
        }
        return $this;
    }

    public function getFiltersCollection($post) {
        $collection = Mage::getResourceModel('dls_dlsblog/post_filter_collection')
                ->addPostFilter($post);
        return $collection;
    }

}
