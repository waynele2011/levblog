<?php

class DLS_Blog_Model_Adminhtml_Observer {

    protected function _canAddTab($product) {
        if ($product->getId()) {
            return true;
        }
        if (!$product->getAttributeSetId()) {
            return false;
        }
        $request = Mage::app()->getRequest();
        if ($request->getParam('type') == 'configurable') {
            if ($request->getParam('attributes')) {
                return true;
            }
        }
        return false;
    }

    public function addProductPostBlock($observer) {
        $block = $observer->getEvent()->getBlock();
        $product = Mage::registry('product');
        if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs && $this->_canAddTab($product)) {
            $block->addTab(
                    'posts', array(
                'label' => Mage::helper('dls_blog')->__('Posts'),
                'url' => Mage::helper('adminhtml')->getUrl(
                        'adminhtml/blog_post_catalog_product/posts', array('_current' => true)
                ),
                'class' => 'ajax',
                    )
            );
        }
        return $this;
    }

    public function saveProductPostData($observer) {
        $post = Mage::app()->getRequest()->getPost('posts', -1);
        if ($post != '-1') {
            $post = Mage::helper('adminhtml/js')->decodeGridSerializedInput($post);
            $product = Mage::registry('product');
            $postProduct = Mage::getResourceSingleton('dls_blog/post_product')
                    ->saveProductRelation($product, $post);
        }
        return $this;
    }

}
