<?php

class DLS_DLSBlog_Block_Blogset_Post_List extends DLS_DLSBlog_Block_Post_List {

    public function __construct() {
        parent::__construct();
        $blogset = $this->getBlogset();
        if ($blogset) {
            $this->getPosts()->addFieldToFilter('blogset_id', $blogset->getId());
        }
    }

    protected function _prepareLayout() {
        return $this;
    }

    public function getBlogset() {
        return Mage::registry('current_blogset');
    }

}
