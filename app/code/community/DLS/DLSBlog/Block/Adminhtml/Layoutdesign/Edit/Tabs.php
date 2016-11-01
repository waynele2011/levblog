<?php

/**
 * Layout design admin edit tabs
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Adminhtml_Layoutdesign_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('layoutdesign_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('dls_dlsblog')->__('Layout Design'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return DLS_DLSBlog_Block_Adminhtml_Layoutdesign_Edit_Tabs
     * @author Ultimate Module Creator
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_layoutdesign',
            array(
                'label'   => Mage::helper('dls_dlsblog')->__('Layout Design'),
                'title'   => Mage::helper('dls_dlsblog')->__('Layout Design'),
                'content' => $this->getLayout()->createBlock(
                    'dls_dlsblog/adminhtml_layoutdesign_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        $this->addTab(
                'form_designer', array(
            'label' => Mage::helper('dls_dlsblog')->__('Designer'),
            'title' => Mage::helper('dls_dlsblog')->__('Designer'),
            'content' => $this->getLayout()->createBlock(
                            'dls_dlsblog/adminhtml_layoutdesign_edit_tab_designer'
                    )
                    ->toHtml(),
                )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve layout design entity
     *
     * @access public
     * @return DLS_DLSBlog_Model_Layoutdesign
     * @author Ultimate Module Creator
     */
    public function getLayoutdesign()
    {
        return Mage::registry('current_layoutdesign');
    }
}
