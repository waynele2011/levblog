<?php

class DLS_Blog_Block_Adminhtml_Layoutdesign_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId('layoutdesign_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('dls_blog')->__('Layout Design'));
    }

    protected function _beforeToHtml() {
        $this->addTab(
                'form_layoutdesign', array(
            'label' => Mage::helper('dls_blog')->__('Layout Design'),
            'title' => Mage::helper('dls_blog')->__('Layout Design'),
            'content' => $this->getLayout()->createBlock(
                            'dls_blog/adminhtml_layoutdesign_edit_tab_form'
                    )
                    ->toHtml(),
                )
        );
        $this->addTab(
                'form_designer', array(
            'label' => Mage::helper('dls_blog')->__('Designer'),
            'title' => Mage::helper('dls_blog')->__('Designer'),
            'content' => $this->getLayout()->createBlock(
                            'dls_blog/adminhtml_layoutdesign_edit_tab_designer'
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
