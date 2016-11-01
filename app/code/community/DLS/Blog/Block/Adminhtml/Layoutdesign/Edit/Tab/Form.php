<?php

class DLS_Blog_Block_Adminhtml_Layoutdesign_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('layoutdesign_');
        $form->setFieldNameSuffix('layoutdesign');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
                'layoutdesign_form', array('legend' => Mage::helper('dls_blog')->__('Layout Design'))
        );

        $fieldset->addField(
                'name', 'text', array(
            'label' => Mage::helper('dls_blog')->__('Name'),
            'name' => 'name',
            'required' => true,
            'class' => 'required-entry',
                )
        );

        $fieldset->addField(
                'description', 'textarea', array(
            'label' => Mage::helper('dls_blog')->__('Description'),
            'name' => 'description',
                )
        );

        $fieldset->addField(
                'status', 'select', array(
            'label' => Mage::helper('dls_blog')->__('Status'),
            'name' => 'status',
            'values' => array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('dls_blog')->__('Enabled'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('dls_blog')->__('Disabled'),
                ),
            ),
                )
        );
        $formValues = Mage::registry('current_layoutdesign')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getLayoutdesignData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getLayoutdesignData());
            Mage::getSingleton('adminhtml/session')->setLayoutdesignData(null);
        } elseif (Mage::registry('current_layoutdesign')) {
            $formValues = array_merge($formValues, Mage::registry('current_layoutdesign')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }

}
