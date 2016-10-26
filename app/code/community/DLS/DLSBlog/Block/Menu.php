<?php

class DLS_DLSBlog_Block_Menu extends DLS_DLSBlog_Block_Taxonomy_List {

    public function __construct() {
        parent::__construct();
        $blogset = $this->getBlogset();
        if ($blogset) {
            $this->getTaxonomies()->addBlogsetFilter($blogset->getId());
            $this->getTaxonomies()->unshiftOrder('related_blogset.position', 'ASC');
        }
    }

    protected function _prepareLayout() {
        return $this;
    }

    public function getBlogset() {
        return Mage::registry('current_blogset');
    }

}
