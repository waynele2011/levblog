<?php

class DLS_Blog_Block_Adminhtml_Post_Comment_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        parent::__construct();
        $this->_blockGroup = 'dls_blog';
        $this->_controller = 'adminhtml_post_comment';
        $this->_updateButton(
                'save', 'label', Mage::helper('dls_blog')->__('Save Post Comment')
        );
        $this->_updateButton(
                'delete', 'label', Mage::helper('dls_blog')->__('Delete Post Comment')
        );
        $this->_addButton(
                'saveandcontinue', array(
            'label' => Mage::helper('dls_blog')->__('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
                ), -100
        );
        $this->_formScripts[] = "
            function saveAndContinueEdit() {
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText() {
        if (Mage::registry('comment_data') && Mage::registry('comment_data')->getId()) {
            return Mage::helper('dls_blog')->__(
                            "Edit Post Comment '%s'", $this->escapeHtml(Mage::registry('comment_data')->getTitle())
            );
        }
        return '';
    }

}
