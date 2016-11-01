<?php

class DLS_Blog_Model_Post_Product extends Mage_Core_Model_Abstract {

    protected function _construct() {
        $this->_init('dls_blog/post_product');
    }

    public function savePostRelation($post) {
        $data = $post->getProductsData();
        if (!is_null($data)) {
            $this->_getResource()->savePostRelation($post, $data);
        }
        return $this;
    }

    public function getProductCollection($post) {
        $collection = Mage::getResourceModel('dls_blog/post_product_collection')
                ->addPostFilter($post);
        return $collection;
    }

}
