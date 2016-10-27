<?php

/**
 * Product helper
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Helper_Product extends DLS_DLSBlog_Helper_Data
{

    /**
     * get the selected posts for a product
     *
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return array()
     * @author Ultimate Module Creator
     */
    public function getSelectedPosts(Mage_Catalog_Model_Product $product)
    {
        if (!$product->hasSelectedPosts()) {
            $posts = array();
            foreach ($this->getSelectedPostsCollection($product) as $post) {
                $posts[] = $post;
            }
            $product->setSelectedPosts($posts);
        }
        return $product->getData('selected_posts');
    }

    /**
     * get post collection for a product
     *
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return DLS_DLSBlog_Model_Resource_Post_Collection
     * @author Ultimate Module Creator
     */
    public function getSelectedPostsCollection(Mage_Catalog_Model_Product $product)
    {
        $collection = Mage::getResourceSingleton('dls_dlsblog/post_collection')
            ->addProductFilter($product);
        return $collection;
    }
}
