<?php

/**
 * Post - product controller
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
require_once ("Mage/Adminhtml/controllers/Catalog/ProductController.php");
class DLS_DLSBlog_Adminhtml_Dlsblog_Post_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController
{
    /**
     * construct
     *
     * @access protected
     * @return void
     * @author Ultimate Module Creator
     */
    protected function _construct()
    {
        // Define module dependent translate
        $this->setUsedModuleName('DLS_DLSBlog');
    }

    /**
     * posts in the catalog page
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function postsAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.post')
            ->setProductPosts($this->getRequest()->getPost('product_posts', null));
        $this->renderLayout();
    }

    /**
     * posts grid in the catalog page
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function postsGridAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.post')
            ->setProductPosts($this->getRequest()->getPost('product_posts', null));
        $this->renderLayout();
    }
}
