<?php

/**
 * Post product model
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Model_Post_Product extends Mage_Core_Model_Abstract
{
    /**
     * Initialize resource
     *
     * @access protected
     * @return void
     * @author Ultimate Module Creator
     */
    protected function _construct()
    {
        $this->_init('dls_dlsblog/post_product');
    }

    /**
     * Save data for post-product relation
     * @access public
     * @param  DLS_DLSBlog_Model_Post $post
     * @return DLS_DLSBlog_Model_Post_Product
     * @author Ultimate Module Creator
     */
    public function savePostRelation($post)
    {
        $data = $post->getProductsData();
        if (!is_null($data)) {
            $this->_getResource()->savePostRelation($post, $data);
        }
        return $this;
    }

    /**
     * get products for post
     *
     * @access public
     * @param DLS_DLSBlog_Model_Post $post
     * @return DLS_DLSBlog_Model_Resource_Post_Product_Collection
     * @author Ultimate Module Creator
     */
    public function getProductCollection($post)
    {
        $collection = Mage::getResourceModel('dls_dlsblog/post_product_collection')
            ->addPostFilter($post);
        return $collection;
    }
}
