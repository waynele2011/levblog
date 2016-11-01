<?php

class DLS_Blog_BlogsetController extends Mage_Core_Controller_Front_Action {

    protected function _initBlogset() {
        $blogsetId = $this->getRequest()->getParam('id', 0);
        $blogset = Mage::getModel('dls_blog/blogset')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($blogsetId);
        if (!$blogset->getId()) {
            return false;
        } elseif (!$blogset->getStatus()) {
            return false;
        }
        return $blogset;
    }

    public function viewAction() {
        $blogset = $this->_initBlogset();
        if (!$blogset) {
            $this->_forward('no-route');
            return;
        }
        Mage::register('current_blogset', $blogset);
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($root = $this->getLayout()->getBlock('root')) {
            $root->addBodyClass('blog-blogset blog-blogset' . $blogset->getId());
            $root->addBodyClass('blog');
        }
        if (Mage::helper('dls_blog/blogset')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                        'home', array(
                    'label' => Mage::helper('dls_blog')->__('Home'),
                    'link' => Mage::getUrl(),
                        )
                );
                $breadcrumbBlock->addCrumb(
                        'blogset', array(
                    'label' => $blogset->getName(),
                    'link' => '',
                        )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', $blogset->getBlogsetUrl());
        }
        $this->renderLayout();
    }

}
