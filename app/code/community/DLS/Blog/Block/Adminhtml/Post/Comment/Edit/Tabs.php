<?php

class DLS_Blog_Block_Adminhtml_Post_Comment_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId('post_comment_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('dls_blog')->__('Post Comment'));
    }

    protected function _beforeToHtml() {
        $this->addTab(
                'form_post_comment', array(
            'label' => Mage::helper('dls_blog')->__('Post Comment'),
            'title' => Mage::helper('dls_blog')->__('Post Comment'),
            'content' => $this->getLayout()->createBlock(
                            'dls_blog/adminhtml_post_comment_edit_tab_form'
                    )
                    ->toHtml(),
                )
        );
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addTab(
                    'form_store_post_comment', array(
                'label' => Mage::helper('dls_blog')->__('Store Views'),
                'title' => Mage::helper('dls_blog')->__('Store Views'),
                'content' => $this->getLayout()->createBlock(
                                'dls_blog/adminhtml_post_comment_edit_tab_stores'
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
