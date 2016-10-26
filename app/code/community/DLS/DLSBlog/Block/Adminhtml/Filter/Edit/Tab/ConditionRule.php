<?php

class DLS_DLSBlog_Block_Adminhtml_Filter_Edit_Tab_Condition extends Mage_Adminhtml_Block_Widget_Form implements Mage_Adminhtml_Block_Widget_Tab_Interface {

    public function getTabLabel() {
        return Mage::helper('dls_dlsblog')->__('Filter Conditions');
    }

    public function getTabTitle() {
        return Mage::helper('dls_dlsblog')->__('Filter Conditions');
    }

    public function canShowTab() {
        return true;
    }

    public function isHidden() {
        return false;
    }

    protected function _prepareForm() {

        $model = Mage::registry('current_promo_quote_rule');


        $form = new Varien_Data_Form();

        $renderer = Mage::getBlockSingleton('adminhtml/widget_form_renderer_fieldset')
                ->setTemplate('promo/fieldset.phtml')
                ->setNewChildUrl($this->getUrl('*/promo_quote/newConditionHtml/form/rule_conditions_fieldset'));


        $fieldset = $form->addFieldset('conditions_fieldset', array(
                    'legend' => Mage::helper('salesrule')->__('Apply the rule only if the following conditions are met (leave blank for all products)')
                ))->setRenderer($renderer);

        $fieldset->addField('conditions', 'text', array(
            'name' => 'conditions',
            'label' => Mage::helper('salesrule')->__('Conditions'),
            'title' => Mage::helper('salesrule')->__('Conditions'),
        ))->setRule($model)->setRenderer(Mage::getBlockSingleton('rule/conditions'));

        $form->setValues($model->getData());

        $this->setForm($form);

        return parent::_prepareForm();
    }

}
