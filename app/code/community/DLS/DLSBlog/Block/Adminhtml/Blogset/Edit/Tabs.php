<?php

/**
 * Blog admin edit tabs
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Adminhtml_Blogset_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('blogset_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('dls_dlsblog')->__('Blog'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return DLS_DLSBlog_Block_Adminhtml_Blogset_Edit_Tabs
     * @author Ultimate Module Creator
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_blogset',
            array(
                'label'   => Mage::helper('dls_dlsblog')->__('Blog'),
                'title'   => Mage::helper('dls_dlsblog')->__('Blog'),
                'content' => $this->getLayout()->createBlock(
                    'dls_dlsblog/adminhtml_blogset_edit_tab_form'
                )
                ->toHtml(),
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
     * Retrieve blog entity
     *
     * @access public
     * @return DLS_DLSBlog_Model_Blogset
     * @author Ultimate Module Creator
     */
    public function getBlogset()
    {
        return Mage::registry('current_blogset');
    }
}
