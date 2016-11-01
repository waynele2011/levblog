<?php

class DLS_Blog_Block_Adminhtml_Post_Attribute_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        $this->_objectId = 'attribute_id';
        $this->_controller = 'adminhtml_post_attribute';
        $this->_blockGroup = 'dls_blog';

        parent::__construct();
        $this->_addButton(
                'save_and_edit_button', array(
            'label' => Mage::helper('dls_blog')->__('Save and Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save'
                ), 100
        );
        $this->_updateButton(
                'save', 'label', Mage::helper('dls_blog')->__('Save Post Attribute')
        );
        $this->_updateButton('save', 'onclick', 'saveAttribute()');

        if (!Mage::registry('entity_attribute')->getIsUserDefined()) {
            $this->_removeButton('delete');
        } else {
            $this->_updateButton(
                    'delete', 'label', Mage::helper('dls_blog')->__('Delete Post Attribute')
            );
        }
    }

    public function getHeaderText() {
        if (Mage::registry('entity_attribute')->getId()) {
            $frontendLabel = Mage::registry('entity_attribute')->getFrontendLabel();
            if (is_array($frontendLabel)) {
                $frontendLabel = $frontendLabel[0];
            }
            return Mage::helper('dls_blog')->__('Edit Post Attribute "%s"', $this->escapeHtml($frontendLabel));
        } else {
            return Mage::helper('dls_blog')->__('New Post Attribute');
        }
    }

    public function getValidationUrl() {
        return $this->getUrl('*/*/validate', array('_current' => true));
    }

    public function getSaveUrl() {
        return $this->getUrl('*/' . $this->_controller . '/save', array('_current' => true, 'back' => null));
    }

}
