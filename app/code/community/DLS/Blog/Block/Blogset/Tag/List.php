<?php

class DLS_Blog_Block_Blogset_Tag_List extends DLS_Blog_Block_Tag_List {

    public function __construct() {
        parent::__construct();
        $blogset = $this->getBlogset();
        if ($blogset) {
            $this->getTags()->addFieldToFilter('blogset_id', $blogset->getId());
        }
    }

    protected function _prepareLayout() {
        return $this;
    }

    public function getBlogset() {
        return Mage::registry('current_blogset');
    }

}
