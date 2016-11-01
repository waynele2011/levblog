<?php

class DLS_Blog_Model_Blogset_Taxonomy extends Mage_Core_Model_Abstract {

    protected function _construct() {
        $this->_init('dls_blog/blogset_taxonomy');
    }

    public function saveBlogsetRelation($blogset) {
        $data = $blogset->getTaxonomiesData();
        if (!is_null($data)) {
            $this->_getResource()->saveBlogsetRelation($blogset, $data);
        }
        return $this;
    }

    public function getTaxonomiesCollection($blogset) {
        $collection = Mage::getResourceModel('dls_blog/blogset_taxonomy_collection')
                ->addBlogsetFilter($blogset);
        return $collection;
    }

}
