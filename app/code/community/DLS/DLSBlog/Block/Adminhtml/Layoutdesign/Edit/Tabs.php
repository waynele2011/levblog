<?php

class DLS_DLSBlog_Block_Adminhtml_Layoutdesign_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId('layoutdesign_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('dls_dlsblog')->__('Layout design'));
    }

    protected function _beforeToHtml() {
        $this->addTab(
                'form_layoutdesign', array(
            'label' => Mage::helper('dls_dlsblog')->__('Settings'),
            'title' => Mage::helper('dls_dlsblog')->__('Settings'),
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

    public function getLayoutdesign() {
        return Mage::registry('current_layoutdesign');
    }

}
