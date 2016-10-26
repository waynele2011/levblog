<?php

class DLS_DLSBlog_Block_Adminhtml_Post_Comment_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId('post_comment_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('dls_dlsblog')->__('Post Comment'));
    }

    protected function _beforeToHtml() {
        $this->addTab(
                'form_post_comment', array(
            'label' => Mage::helper('dls_dlsblog')->__('Post comment'),
            'title' => Mage::helper('dls_dlsblog')->__('Post comment'),
            'content' => $this->getLayout()->createBlock(
                            'dls_dlsblog/adminhtml_post_comment_edit_tab_form'
                    )
                    ->toHtml(),
                )
        );
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addTab(
                    'form_store_post_comment', array(
                'label' => Mage::helper('dls_dlsblog')->__('Store views'),
                'title' => Mage::helper('dls_dlsblog')->__('Store views'),
                'content' => $this->getLayout()->createBlock(
                                'dls_dlsblog/adminhtml_post_comment_edit_tab_stores'
                        )
                        ->toHtml(),
                    )
            );
        }
        return parent::_beforeToHtml();
    }

    public function getComment() {
        return Mage::registry('current_comment');
    }

}
