<?php

class DLS_DLSBlog_Block_Adminhtml_Blogset_Edit_Tab_Meta extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $form->setFieldNameSuffix('blogset');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
                'blogset_meta_form', array('legend' => Mage::helper('dls_dlsblog')->__('Meta information'))
        );
        $fieldset->addField(
                'meta_title', 'text', array(
            'label' => Mage::helper('dls_dlsblog')->__('Meta-title'),
            'name' => 'meta_title',
                )
        );
        $fieldset->addField(
                'meta_description', 'textarea', array(
            'name' => 'meta_description',
            'label' => Mage::helper('dls_dlsblog')->__('Meta-description'),
                )
        );
        $fieldset->addField(
                'meta_keywords', 'textarea', array(
            'name' => 'meta_keywords',
            'label' => Mage::helper('dls_dlsblog')->__('Meta-keywords'),
                )
        );
        $form->addValues(Mage::registry('current_blogset')->getData());
        return parent::_prepareForm();
    }

}
