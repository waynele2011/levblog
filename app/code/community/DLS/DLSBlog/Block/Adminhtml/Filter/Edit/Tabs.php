<?php

/**
 * Filter admin edit tabs
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Adminhtml_Filter_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Initialize Tabs
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('filter_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('dls_dlsblog')->__('Filter'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return DLS_DLSBlog_Block_Adminhtml_Filter_Edit_Tabs
     * @author Ultimate Module Creator
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_filter',
            array(
                'label'   => Mage::helper('dls_dlsblog')->__('Filter'),
                'title'   => Mage::helper('dls_dlsblog')->__('Filter'),
                'content' => $this->getLayout()->createBlock(
                    'dls_dlsblog/adminhtml_filter_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        
        $this->addTab(
            'form_meta_filter',
            array(
                'label'   => Mage::helper('dls_dlsblog')->__('Meta'),
                'title'   => Mage::helper('dls_dlsblog')->__('Meta'),
                'content' => $this->getLayout()->createBlock(
                    'dls_dlsblog/adminhtml_filter_edit_tab_meta'
                )
                ->toHtml(),
            )
        );
        
        $this->addTab(
            'form_condition_filter', array(
                'label' => Mage::helper('dls_dlsblog')->__('Filter Conditions'),
                'title' => Mage::helper('dls_dlsblog')->__('Filter Conditions'),
                'content' => $this->getLayout()->createBlock(
                    'dls_dlsblog/adminhtml_filter_edit_tab_condition'
                    )->toHtml(),
            )
        );
        
        $this->addTab(
            'taxonomies',
            array(
                'label' => Mage::helper('dls_dlsblog')->__('Taxonomies'),
                'url'   => $this->getUrl('*/*/taxonomies', array('_current' => true)),
                'class' => 'ajax'
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve filter entity
     *
     * @access public
     * @return DLS_DLSBlog_Model_Filter
     * @author Ultimate Module Creator
     */
    public function getFilter()
    {
        return Mage::registry('current_filter');
    }
}
