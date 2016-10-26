<?php

class DLS_DLSBlog_Model_Filter_Post extends Mage_Core_Model_Abstract {

    protected function _construct() {
        $this->_init('dls_dlsblog/filter_post');
    }

    public function saveFilterRelation($filter) {
        $data = $filter->getPostsData();
        if (!is_null($data)) {
            $this->_getResource()->saveFilterRelation($filter, $data);
        }
        return $this;
    }

    public function getPostsCollection($filter) {
        $collection = Mage::getResourceModel('dls_dlsblog/filter_post_collection')
                ->addFilterFilter($filter);
        return $collection;
    }

}
