<?php

/**
 * Post list on product page block
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Catalog_Product_List_Post extends Mage_Catalog_Block_Product_Abstract
{
    /**
     * get the list of posts
     *
     * @access protected
     * @return DLS_DLSBlog_Model_Resource_Post_Collection
     * @author Ultimate Module Creator
     */
    public function getPostCollection()
    {
        if (!$this->hasData('post_collection')) {
            $product = Mage::registry('product');
            $collection = Mage::getResourceSingleton('dls_dlsblog/post_collection')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->addAttributeToSelect('title', 1)
                ->addAttributeToFilter('status', 1)
                ->addProductFilter($product);
            $collection->getSelect()->order('related_product.position', 'ASC');
            $this->setData('post_collection', $collection);
        }
        return $this->getData('post_collection');
    }
}
