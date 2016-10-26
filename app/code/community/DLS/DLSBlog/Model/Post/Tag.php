<?php

class DLS_DLSBlog_Model_Post_Tag extends Mage_Core_Model_Abstract {

    protected function _construct() {
        $this->_init('dls_dlsblog/post_tag');
    }

    public function savePostRelation($post) {
        $data = $post->getTagsData();
        if (!is_null($data)) {
            $this->_getResource()->savePostRelation($post, $data);
        }
        return $this;
    }

    public function getTagsCollection($post) {
        $collection = Mage::getResourceModel('dls_dlsblog/post_tag_collection')
                ->addPostFilter($post);
        return $collection;
    }

}
