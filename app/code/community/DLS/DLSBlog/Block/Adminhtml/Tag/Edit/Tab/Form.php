<?php

class DLS_DLSBlog_Block_Adminhtml_Tag_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('tag_');
        $form->setFieldNameSuffix('tag');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
                'tag_form', array('legend' => Mage::helper('dls_dlsblog')->__('Tag'))
        );

        $fieldset->addField(
                'slug', 'text', array(
            'label' => Mage::helper('dls_dlsblog')->__('Slug'),
            'name' => 'slug',
            'required' => true,
            'class' => 'required-entry',
                )
        );

        $fieldset->addField(
                'name', 'text', array(
            'label' => Mage::helper('dls_dlsblog')->__('Name'),
            'name' => 'name',
            'required' => true,
            'class' => 'required-entry',
                )
        );
        $fieldset->addField(
                'status', 'select', array(
            'label' => Mage::helper('dls_dlsblog')->__('Status'),
            'name' => 'status',
            'values' => array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('dls_dlsblog')->__('Enabled'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('dls_dlsblog')->__('Disabled'),
                ),
            ),
                )
        );
        $formValues = Mage::registry('current_tag')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getTagData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getTagData());
            Mage::getSingleton('adminhtml/session')->setTagData(null);
        } elseif (Mage::registry('current_tag')) {
            $formValues = array_merge($formValues, Mage::registry('current_tag')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }

}
