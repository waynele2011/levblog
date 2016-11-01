<?php

class DLS_Blog_Block_Menu extends DLS_Blog_Block_Taxonomy_List {

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
        $curent_blogset = Mage::registry('current_blogset');
        if (empty($curent_blogset)) {
            $_blogsets = Mage::getModel('dls_blog/blogset')->getCollection()->addFieldToFilter('status', 1);
            if ($_blogsets->count() > 0)
                $curent_blogset = $_blogsets->getFirstItem();
        }
        return $curent_blogset;
    }

    public function getTaxonomiesArray() {
        $taxonomies = Mage::getResourceModel('dls_blog/taxonomy_collection')
                ->addFieldToFilter('status', 1);
        $taxonomies->getSelect()->order('main_table.position');
        return $taxonomies;
    }

}
