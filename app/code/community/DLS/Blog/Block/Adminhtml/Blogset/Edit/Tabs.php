<?php

class DLS_Blog_Block_Adminhtml_Blogset_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId('blogset_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('dls_blog')->__('Blog'));
    }

    protected function _beforeToHtml() {
        $this->addTab(
                'form_blogset', array(
            'label' => Mage::helper('dls_blog')->__('Blog'),
            'title' => Mage::helper('dls_blog')->__('Blog'),
            'content' => $this->getLayout()->createBlock(
                            'dls_blog/adminhtml_blogset_edit_tab_form'
                    )
                    ->toHtml(),
                )
        );
        $this->addTab(
                'taxonomies', array(
            'label' => Mage::helper('dls_blog')->__('Categories'),
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
