<?php

class DLS_Blog_Block_Adminhtml_Helper_Wysiwyg extends Varien_Data_Form_Element_Textarea {

    public function getAfterElementHtml() {
        $html = parent::getAfterElementHtml();
        $disabled = ($this->getDisabled() || $this->getReadonly());
        $html .= Mage::getSingleton('core/layout')
                ->createBlock(
                        'adminhtml/widget_button', '', array(
                    'label' => Mage::helper('catalog')->__('WYSIWYG Editor'),
                    'type' => 'button',
                    'disabled' => $disabled,
                    'class' => ($disabled) ? 'disabled btn-wysiwyg' : 'btn-wysiwyg',
                    'onclick' => 'catalogWysiwygEditor.open(\'' .
                    Mage::helper('adminhtml')->getUrl('*/*/wysiwyg') . '\', \'' .
                    $this->getHtmlId() . '\')'
                        )
                )
                ->toHtml();
        return $html;
    }

}
