<?php

class DLS_DLSBlog_Block_Adminhtml_Layoutdesign_Edit_Tab_Designer extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $form->setFieldNameSuffix('layoutdesign');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
                'layoutdesign_designer_form', array(
            'legend' => Mage::helper('dls_dlsblog')->__('Designer'),
                )
        );

        $fieldset->addField(
                'basic_layout', 'select', array(
            'label' => Mage::helper('dls_dlsblog')->__('Basic layout'),
            'name' => 'basic_layout',
            'required' => true,
            'class' => 'required-entry',
            'values' => Mage::getModel('dls_dlsblog/layoutdesign_attribute_source_basiclayout')->getAllOptions(true),
                )
        );

        $fieldset->addType('designer_frame', 'DLS_DLSBlog_Block_Adminhtml_Layoutdesign_Renderer_Designer');
        $fieldset->addField('design_code', 'designer_frame', array(
            'name' => 'design_code',
            'label' => Mage::helper('dls_dlsblog')->__('Design frame'),
        ));

        $form->addValues(Mage::registry('current_layoutdesign')->getData());
        return parent::_prepareForm();
    }

}
