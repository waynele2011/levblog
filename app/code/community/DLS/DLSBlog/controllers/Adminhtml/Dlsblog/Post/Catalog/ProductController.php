<?php

require_once ("Mage/Adminhtml/controllers/Catalog/ProductController.php");

class DLS_DLSBlog_Adminhtml_Dlsblog_Post_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController {

    protected function _construct() {
        // Define module dependent translate
        $this->setUsedModuleName('DLS_DLSBlog');
    }

    public function postsAction() {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.post')
                ->setProductPosts($this->getRequest()->getPost('product_posts', null));
        $this->renderLayout();
    }

    public function postsGridAction() {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.post')
                ->setProductPosts($this->getRequest()->getPost('product_posts', null));
        $this->renderLayout();
    }

}
