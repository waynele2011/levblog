<?php

class DLS_DLSBlog_Block_Adminhtml_Filter_Edit_Tab_Condition extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $form->setFieldNameSuffix('filter');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
                'filter_condition_form', array('legend' => Mage::helper('dls_dlsblog')->__('Contidions'))
        );
        $fieldset->addField(
                'exposed', 'select', array(
            'label' => Mage::helper('dls_dlsblog')->__('Exposed filter'),
            'name' => 'exposed',
            'required' => true,
            'class' => 'required-entry',
            'values' => array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('dls_dlsblog')->__('Yes'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('dls_dlsblog')->__('No'),
                ),
            ),
                )
        );

        $fieldset->addField(
                'type', 'select', array(
            'label' => Mage::helper('dls_dlsblog')->__('Filter type'),
            'name' => 'type',
            'required' => true,
            'class' => 'required-entry',
            'values' => Mage::getModel('dls_dlsblog/filter_attribute_source_type')->getAllOptions(true),
                )
        );

        $fieldset->addType('condition_frame', 'DLS_DLSBlog_Block_Adminhtml_Filter_Renderer_Condition');
        $fieldset->addField('condition_code', 'condition_frame', array(
            'name' => 'condition_code',
            'label' => Mage::helper('dls_dlsblog')->__('Condition'),
        ));

        $fieldset->addField(
                'sort_code', 'textarea', array(
            'label' => Mage::helper('dls_dlsblog')->__('Sorts'),
            'name' => 'sort_code',
                )
        );

        $fieldset->addField(
                'paging_code', 'textarea', array(
            'label' => Mage::helper('dls_dlsblog')->__('Paging'),
            'name' => 'paging_code',
                )
        );


        $form->addValues(Mage::registry('current_filter')->getData());
        return parent::_prepareForm();
    }

}
