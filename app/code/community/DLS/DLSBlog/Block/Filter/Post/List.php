<?php

class DLS_DLSBlog_Block_Filter_Post_List extends DLS_DLSBlog_Block_Post_List {

    public function __construct() {
        parent::__construct();
        $filter = $this->getFilter();
        if ($filter) {
            $this->getPosts()->addFilterFilter($filter->getId());
            $this->getPosts()->unshiftOrder('related_filter.position', 'ASC');
        }
    }

    protected function _prepareLayout() {
        return $this;
    }

    public function getFilter() {
        return Mage::registry('current_filter');
    }

}
