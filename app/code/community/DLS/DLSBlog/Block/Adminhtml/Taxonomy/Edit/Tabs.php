<?php

/**
 * Category admin edit tabs
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Adminhtml_Taxonomy_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Initialize Tabs
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        $this->setId('taxonomy_info_tabs');
        $this->setDestElementId('taxonomy_tab_content');
        $this->setTitle(Mage::helper('dls_dlsblog')->__('Category'));
        $this->setTemplate('widget/tabshoriz.phtml');
    }

    /**
     * Prepare Layout Content
     *
     * @access public
     * @return DLS_DLSBlog_Block_Adminhtml_Taxonomy_Edit_Tabs
     */
    protected function _prepareLayout()
    {
        $this->addTab(
            'form_taxonomy',
            array(
                'label'   => Mage::helper('dls_dlsblog')->__('Category'),
                'title'   => Mage::helper('dls_dlsblog')->__('Category'),
                'content' => $this->getLayout()->createBlock(
                    'dls_dlsblog/adminhtml_taxonomy_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        $this->addTab(
            'form_meta_taxonomy',
            array(
                'label'   => Mage::helper('dls_dlsblog')->__('Meta'),
                'title'   => Mage::helper('dls_dlsblog')->__('Meta'),
                'content' => $this->getLayout()->createBlock(
                    'dls_dlsblog/adminhtml_taxonomy_edit_tab_meta'
                )
                ->toHtml(),
            )
        );
        
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve taxonomy entity
     *
     * @access public
     * @return DLS_DLSBlog_Model_Taxonomy
     * @author Ultimate Module Creator
     */
    public function getTaxonomy()
    {
        return Mage::registry('current_taxonomy');
    }
}
