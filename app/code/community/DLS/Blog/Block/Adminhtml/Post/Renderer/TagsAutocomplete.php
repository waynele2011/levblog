<?php

class DLS_Blog_Block_Adminhtml_Post_Renderer_TagsAutocomplete extends Varien_Data_Form_Element_Text {

    protected $_element;

    public function getElementHtml() {

        $this->addClass('input-text');
        $html = '<input id="' . $this->getHtmlId() . '" name="' . $this->getName()
                . '" value="' . $this->getEscapedValue() . '" ' . $this->serialize($this->getHtmlAttributes()) . '/>' . "\n";
        $html.= $this->getAfterElementHtml();

        $html .= Mage::app()->getLayout()
                ->createBlock('dls_blog/adminhtml_form_renderer_tags')
                //->setData('element', $elementOptions)
                ->setTemplate('dls_blog/form/renderer/tags-autocomplete.phtml')
                ->toHtml();
        return $html;
    }

}
