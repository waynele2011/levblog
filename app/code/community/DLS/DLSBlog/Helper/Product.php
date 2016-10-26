<?php

class DLS_DLSBlog_Helper_Product extends DLS_DLSBlog_Helper_Data {

    public function getSelectedPosts(Mage_Catalog_Model_Product $product) {
        if (!$product->hasSelectedPosts()) {
            $posts = array();
            foreach ($this->getSelectedPostsCollection($product) as $post) {
                $posts[] = $post;
            }
            $product->setSelectedPosts($posts);
        }
        return $product->getData('selected_posts');
    }

    public function getSelectedPostsCollection(Mage_Catalog_Model_Product $product) {
        $collection = Mage::getResourceSingleton('dls_dlsblog/post_collection')
                ->addProductFilter($product);
        return $collection;
    }

}
