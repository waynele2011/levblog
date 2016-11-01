<?php

class DLS_Blog_Block_Adminhtml_Blogset_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        parent::__construct();
        $this->_blockGroup = 'dls_blog';
        $this->_controller = 'adminhtml_blogset';
        $this->_updateButton(
                'save', 'label', Mage::helper('dls_blog')->__('Save Blog')
        );
        $this->_updateButton(
                'delete', 'label', Mage::helper('dls_blog')->__('Delete Blog')
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
        if (Mage::registry('current_blogset') && Mage::registry('current_blogset')->getId()) {
            return Mage::helper('dls_blog')->__(
                            "Edit Blog '%s'", $this->escapeHtml(Mage::registry('current_blogset')->getName())
            );
        } else {
            return Mage::helper('dls_blog')->__('Add Blog');
        }
    }

}
