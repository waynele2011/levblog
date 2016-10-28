<?php

/**
 * Taxonomy front contrller
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_TaxonomyController extends Mage_Core_Controller_Front_Action
{

    /**
     * init Taxonomy
     *
     * @access protected
     * @return DLS_DLSBlog_Model_Taxonomy
     * @author Ultimate Module Creator
     */
    protected function _initTaxonomy()
    {
        $taxonomyId   = $this->getRequest()->getParam('id', 0);
        $taxonomy     = Mage::getModel('dls_dlsblog/taxonomy')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($taxonomyId);
        if (!$taxonomy->getId()) {
            return false;
        } elseif (!$taxonomy->getStatus()) {
            return false;
        }
        return $taxonomy;
    }

    /**
     * view taxonomy action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function viewAction()
    {
        $taxonomy = $this->_initTaxonomy();
        if (!$taxonomy) {
            $this->_forward('no-route');
            return;
        }
        if (!$taxonomy->getStatusPath()) {
            $this->_forward('no-route');
            return;
        }
        Mage::register('current_taxonomy', $taxonomy);
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($root = $this->getLayout()->getBlock('root')) {
            $root->addBodyClass('dlsblog-taxonomy dlsblog-taxonomy' . $taxonomy->getId());
            $root->addBodyClass('dlsblog');
        }
        if (Mage::helper('dls_dlsblog/taxonomy')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    array(
                        'label'    => Mage::helper('dls_dlsblog')->__('Home'),
                        'link'     => Mage::getUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'taxonomy',
                    array(
                        'label' => $taxonomy->getName(),
                        'link'  => '',
                    )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', $taxonomy->getTaxonomyUrl());
        }
        if ($headBlock) {
            if ($taxonomy->getMetaTitle()) {
                $headBlock->setTitle($taxonomy->getMetaTitle());
            } else {
                $headBlock->setTitle($taxonomy->getName());
            }
            $headBlock->setKeywords($taxonomy->getMetaKeywords());
            $headBlock->setDescription($taxonomy->getMetaDescription());
        }
        $this->renderLayout();
    }
}
