<?php

/**
 * Adminhtml observer
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Model_Adminhtml_Observer
{
    /**
     * check if tab can be added
     *
     * @access protected
     * @param Mage_Catalog_Model_Product $product
     * @return bool
     * @author Ultimate Module Creator
     */
    protected function _canAddTab($product)
    {
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

    /**
     * add the post tab to products
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return DLS_DLSBlog_Model_Adminhtml_Observer
     * @author Ultimate Module Creator
     */
    public function addProductPostBlock($observer)
    {
        $block = $observer->getEvent()->getBlock();
        $product = Mage::registry('product');
        if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs && $this->_canAddTab($product)) {
            $block->addTab(
                'posts',
                array(
                    'label' => Mage::helper('dls_dlsblog')->__('Posts'),
                    'url'   => Mage::helper('adminhtml')->getUrl(
                        'adminhtml/dlsblog_post_catalog_product/posts',
                        array('_current' => true)
                    ),
                    'class' => 'ajax',
                )
            );
        }
        return $this;
    }

    /**
     * save post - product relation
     * @access public
     * @param Varien_Event_Observer $observer
     * @return DLS_DLSBlog_Model_Adminhtml_Observer
     * @author Ultimate Module Creator
     */
    public function saveProductPostData($observer)
    {
        $post = Mage::app()->getRequest()->getPost('posts', -1);
        if ($post != '-1') {
            $post = Mage::helper('adminhtml/js')->decodeGridSerializedInput($post);
            $product = Mage::registry('product');
            $postProduct = Mage::getResourceSingleton('dls_dlsblog/post_product')
                ->saveProductRelation($product, $post);
        }
        return $this;
    }}
