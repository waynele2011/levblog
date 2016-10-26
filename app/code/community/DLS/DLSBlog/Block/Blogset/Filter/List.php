<?php

class DLS_DLSBlog_Block_Blogset_Filter_List extends DLS_DLSBlog_Block_Filter_List {

    public function __construct() {
        parent::__construct();
        $blogset = $this->getBlogset();
        if ($blogset) {
            $this->getFilters()->addFieldToFilter('blogset_id', $blogset->getId());
        }
    }

    protected function _prepareLayout() {
        return $this;
    }

    public function getBlogset() {
        return Mage::registry('current_blogset');
    }

    public function getFilterContent(){
        return Mage::getModel('dls_dlsblog/filter')->getFilterById(1);
    }
}
