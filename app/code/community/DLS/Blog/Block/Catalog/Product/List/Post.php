<?php

class DLS_Blog_Block_Catalog_Product_List_Post extends Mage_Catalog_Block_Product_Abstract {

    public function getPostCollection() {
        if (!$this->hasData('post_collection')) {
            $product = Mage::registry('product');
            $collection = Mage::getResourceSingleton('dls_blog/post_collection')
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
