<?php

require_once ("Mage/Adminhtml/controllers/Catalog/ProductController.php");

class DLS_Blog_Adminhtml_Blog_Post_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController {

    protected function _construct() {
        // Define module dependent translate
        $this->setUsedModuleName('DLS_Blog');
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
