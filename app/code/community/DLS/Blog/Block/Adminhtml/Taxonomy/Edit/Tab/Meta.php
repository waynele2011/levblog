<?php

class DLS_Blog_Block_Adminhtml_Taxonomy_Edit_Tab_Meta extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $form->setFieldNameSuffix('taxonomy');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
                'taxonomy_meta_form', array('legend' => Mage::helper('dls_blog')->__('Meta Information'))
        );
        $fieldset->addField(
                'meta_title', 'text', array(
            'label' => Mage::helper('dls_blog')->__('Meta-title'),
            'name' => 'meta_title',
                )
        );
        $fieldset->addField(
                'meta_description', 'textarea', array(
            'name' => 'meta_description',
            'label' => Mage::helper('dls_blog')->__('Meta-description'),
                )
        );
        $fieldset->addField(
                'meta_keywords', 'textarea', array(
            'name' => 'meta_keywords',
            'label' => Mage::helper('dls_blog')->__('Meta-keywords'),
                )
        );
        $form->addValues(Mage::registry('current_taxonomy')->getData());
        return parent::_prepareForm();
    }

}
