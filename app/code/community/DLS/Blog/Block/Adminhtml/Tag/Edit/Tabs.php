<?php

class DLS_Blog_Block_Adminhtml_Tag_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId('tag_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('dls_blog')->__('Tag'));
    }

    protected function _beforeToHtml() {
        $this->addTab(
                'form_tag', array(
            'label' => Mage::helper('dls_blog')->__('Tag'),
            'title' => Mage::helper('dls_blog')->__('Tag'),
            'content' => $this->getLayout()->createBlock(
                            'dls_blog/adminhtml_tag_edit_tab_form'
                    )
                    ->toHtml(),
                )
        );
        $this->addTab(
                'posts', array(
            'label' => Mage::helper('dls_blog')->__('Posts'),
            'url' => $this->getUrl('*/*/posts', array('_current' => true)),
            'class' => 'ajax'
                )
        );
        return parent::_beforeToHtml();
    }

    public function getTag() {
        return Mage::registry('current_tag');
    }

}
