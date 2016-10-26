<?php

class DLS_DLSBlog_Model_Post_Product extends Mage_Core_Model_Abstract {

    protected function _construct() {
        $this->_init('dls_dlsblog/post_product');
    }

    public function savePostRelation($post) {
        $data = $post->getProductsData();
        if (!is_null($data)) {
            $this->_getResource()->savePostRelation($post, $data);
        }
        return $this;
    }

    public function getProductCollection($post) {
        $collection = Mage::getResourceModel('dls_dlsblog/post_product_collection')
                ->addPostFilter($post);
        return $collection;
    }

}
