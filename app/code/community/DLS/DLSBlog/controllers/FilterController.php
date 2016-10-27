<?php

/**
 * Filter front contrller
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_FilterController extends Mage_Core_Controller_Front_Action
{

    /**
     * init Filter
     *
     * @access protected
     * @return DLS_DLSBlog_Model_Filter
     * @author Ultimate Module Creator
     */
    protected function _initFilter()
    {
        $filterId   = $this->getRequest()->getParam('id', 0);
        $filter     = Mage::getModel('dls_dlsblog/filter')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($filterId);
        if (!$filter->getId()) {
            return false;
        } elseif (!$filter->getStatus()) {
            return false;
        }
        return $filter;
    }

    /**
     * view filter action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function viewAction()
    {
        $filter = $this->_initFilter();
        if (!$filter) {
            $this->_forward('no-route');
            return;
        }
        Mage::register('current_filter', $filter);
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($root = $this->getLayout()->getBlock('root')) {
            $root->addBodyClass('dlsblog-filter dlsblog-filter' . $filter->getId());
        }
        if (Mage::helper('dls_dlsblog/filter')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    array(
                        'label'    => Mage::helper('dls_dlsblog')->__('Home'),
                        'link'     => Mage::getUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'filter',
                    array(
                        'label' => $filter->getName(),
                        'link'  => '',
                    )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', $filter->getFilterUrl());
        }
        if ($headBlock) {
            if ($filter->getMetaTitle()) {
                $headBlock->setTitle($filter->getMetaTitle());
            } else {
                $headBlock->setTitle($filter->getName());
            }
            $headBlock->setKeywords($filter->getMetaKeywords());
            $headBlock->setDescription($filter->getMetaDescription());
        }
        $this->renderLayout();
    }
}
