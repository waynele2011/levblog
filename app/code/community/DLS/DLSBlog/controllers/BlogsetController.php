<?php

/**
 * Blog front contrller
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_BlogsetController extends Mage_Core_Controller_Front_Action
{

    /**
     * init Blog
     *
     * @access protected
     * @return DLS_DLSBlog_Model_Blogset
     * @author Ultimate Module Creator
     */
    protected function _initBlogset()
    {
        $blogsetId   = $this->getRequest()->getParam('id', 0);
        $blogset     = Mage::getModel('dls_dlsblog/blogset')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($blogsetId);
        if (!$blogset->getId()) {
            return false;
        } elseif (!$blogset->getStatus()) {
            return false;
        }
        return $blogset;
    }

    /**
     * view blog action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function viewAction()
    {
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
            $root->addBodyClass('dlsblog-blogset dlsblog-blogset' . $blogset->getId());
            $root->addBodyClass('dlsblog');
        }
        if (Mage::helper('dls_dlsblog/blogset')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    array(
                        'label'    => Mage::helper('dls_dlsblog')->__('Home'),
                        'link'     => Mage::getUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'blogset',
                    array(
                        'label' => $blogset->getName(),
                        'link'  => '',
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
