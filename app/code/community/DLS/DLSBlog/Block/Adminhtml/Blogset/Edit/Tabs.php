<?php

class DLS_DLSBlog_Block_Adminhtml_Blogset_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId('blogset_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('dls_dlsblog')->__('Blog setting'));
    }

    protected function _beforeToHtml() {
        $this->addTab(
                'form_blogset', array(
            'label' => Mage::helper('dls_dlsblog')->__('Blog setting'),
            'title' => Mage::helper('dls_dlsblog')->__('Blog setting'),
            'content' => $this->getLayout()->createBlock(
                            'dls_dlsblog/adminhtml_blogset_edit_tab_form'
                    )
                    ->toHtml(),
                )
        );
        $this->addTab(
                'form_meta_blogset', array(
            'label' => Mage::helper('dls_dlsblog')->__('Meta'),
            'title' => Mage::helper('dls_dlsblog')->__('Meta'),
            'content' => $this->getLayout()->createBlock(
                            'dls_dlsblog/adminhtml_blogset_edit_tab_meta'
                    )
                    ->toHtml(),
                )
        );
        $this->addTab(
                'taxonomies', array(
            'label' => Mage::helper('dls_dlsblog')->__('Taxonomies'),
            'url' => $this->getUrl('*/*/taxonomies', array('_current' => true)),
            'class' => 'ajax'
                )
        );
        return parent::_beforeToHtml();
    }

    public function getBlogset() {
        return Mage::registry('current_blogset');
    }

}
