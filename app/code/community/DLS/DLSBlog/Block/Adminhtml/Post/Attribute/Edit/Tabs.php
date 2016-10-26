<?php

class DLS_DLSBlog_Block_Adminhtml_Post_Attribute_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId('post_attribute_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('dls_dlsblog')->__('Attribute Information'));
    }

    protected function _beforeToHtml() {
        $this->addTab(
                'main', array(
            'label' => Mage::helper('dls_dlsblog')->__('Properties'),
            'title' => Mage::helper('dls_dlsblog')->__('Properties'),
            'content' => $this->getLayout()->createBlock(
                            'dls_dlsblog/adminhtml_post_attribute_edit_tab_main'
                    )
                    ->toHtml(),
            'active' => true
                )
        );
        $this->addTab(
                'labels', array(
            'label' => Mage::helper('dls_dlsblog')->__('Manage Label / Options'),
            'title' => Mage::helper('dls_dlsblog')->__('Manage Label / Options'),
            'content' => $this->getLayout()->createBlock(
                            'dls_dlsblog/adminhtml_post_attribute_edit_tab_options'
                    )
                    ->toHtml(),
                )
        );
        return parent::_beforeToHtml();
    }

}
