<?php

class DLS_DLSBlog_Model_Tag_Post extends Mage_Core_Model_Abstract {

    protected function _construct() {
        $this->_init('dls_dlsblog/tag_post');
    }

    public function saveTagRelation($tag) {
        $data = $tag->getPostsData();
        if (!is_null($data)) {
            $this->_getResource()->saveTagRelation($tag, $data);
        }
        return $this;
    }

    public function getPostsCollection($tag) {
        $collection = Mage::getResourceModel('dls_dlsblog/tag_post_collection')
                ->addTagFilter($tag);
        return $collection;
    }

}
