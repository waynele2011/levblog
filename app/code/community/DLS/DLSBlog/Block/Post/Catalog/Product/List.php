<?php

class DLS_DLSBlog_Block_Post_Catalog_Product_List extends Mage_Core_Block_Template {

    public function getProductCollection() {
        $collection = $this->getPost()->getSelectedProductsCollection();
        $collection->addAttributeToSelect('name');
        $collection->addUrlRewrite();
        $collection->getSelect()->order('related.position');
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);
        return $collection;
    }

    public function getPost() {
        return Mage::registry('current_post');
    }

}
