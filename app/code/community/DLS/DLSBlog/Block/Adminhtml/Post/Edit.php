<?php

class DLS_DLSBlog_Block_Adminhtml_Post_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        parent::__construct();
        $this->_blockGroup = 'dls_dlsblog';
        $this->_controller = 'adminhtml_post';
        $this->_updateButton(
                'save', 'label', Mage::helper('dls_dlsblog')->__('Save Post')
        );
        $this->_updateButton(
                'delete', 'label', Mage::helper('dls_dlsblog')->__('Delete Post')
        );
        $this->_addButton(
                'saveandcontinue', array(
            'label' => Mage::helper('dls_dlsblog')->__('Save And Continue Edit'),
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
        if (Mage::registry('current_post') && Mage::registry('current_post')->getId()) {
            return Mage::helper('dls_dlsblog')->__(
                            "Edit Post '%s'", $this->escapeHtml(Mage::registry('current_post')->getTitle())
            );
        } else {
            return Mage::helper('dls_dlsblog')->__('Add Post');
        }
    }

}
