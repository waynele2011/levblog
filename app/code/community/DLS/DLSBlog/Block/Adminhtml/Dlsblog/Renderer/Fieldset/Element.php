<?php

class DLS_DLSBlog_Block_Adminhtml_DLSBlog_Renderer_Fieldset_Element extends Mage_Adminhtml_Block_Widget_Form_Renderer_Fieldset_Element {

    protected function _construct() {
        $this->setTemplate('dls_dlsblog/form/renderer/fieldset/element.phtml');
    }

    public function getDataObject() {
        return $this->getElement()->getForm()->getDataObject();
    }

    public function getAttribute() {
        return $this->getElement()->getEntityAttribute();
    }

    public function getAttributeCode() {
        return $this->getAttribute()->getAttributeCode();
    }

    public function canDisplayUseDefault() {
        if ($attribute = $this->getAttribute()) {
            if (!$this->isScopeGlobal($attribute) && $this->getDataObject() && $this->getDataObject()->getId() && $this->getDataObject()->getStoreId()) {
                return true;
            }
        }
        return false;
    }

    public function usedDefault() {
        $defaultValue = $this->getDataObject()->getAttributeDefaultValue($this->getAttribute()->getAttributeCode());
        return !$defaultValue;
    }

    public function checkFieldDisable() {
        if ($this->canDisplayUseDefault() && $this->usedDefault()) {
            $this->getElement()->setDisabled(true);
        }
        return $this;
    }

    public function getScopeLabel() {
        $html = '';
        $attribute = $this->getElement()->getEntityAttribute();
        if (!$attribute || Mage::app()->isSingleStoreMode()) {
            return $html;
        }
        if ($this->isScopeGlobal($attribute)) {
            $html.= Mage::helper('dls_dlsblog')->__('[GLOBAL]');
        } elseif ($this->isScopeWebsite($attribute)) {
            $html.= Mage::helper('dls_dlsblog')->__('[WEBSITE]');
        } elseif ($this->isScopeStore($attribute)) {
            $html.= Mage::helper('dls_dlsblog')->__('[STORE VIEW]');
        }
        return $html;
    }

    public function getElementLabelHtml() {
        return $this->getElement()->getLabelHtml();
    }

    public function getElementHtml() {
        return $this->getElement()->getElementHtml();
    }

    public function isScopeGlobal($attribute) {
        return $attribute->getIsGlobal() == 1;
    }

    public function isScopeWebsite($attribute) {
        return $attribute->getIsGlobal() == 2;
    }

    public function isScopeStore($attribute) {
        return !$this->isScopeGlobal($attribute) && !$this->isScopeWebsite($attribute);
    }

}
