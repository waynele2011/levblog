<?php

class DLS_Blog_Block_Adminhtml_Layoutdesign_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        parent::__construct();
        $this->_blockGroup = 'dls_blog';
        $this->_controller = 'adminhtml_layoutdesign';
        $this->_updateButton(
                'save', 'label', Mage::helper('dls_blog')->__('Save Layout Design')
        );
        $this->_updateButton(
                'delete', 'label', Mage::helper('dls_blog')->__('Delete Layout Design')
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
        if (Mage::registry('current_layoutdesign') && Mage::registry('current_layoutdesign')->getId()) {
            return Mage::helper('dls_blog')->__(
                            "Edit Layout Design '%s'", $this->escapeHtml(Mage::registry('current_layoutdesign')->getName())
            );
        } else {
            return Mage::helper('dls_blog')->__('Add Layout Design');
        }
    }

}
