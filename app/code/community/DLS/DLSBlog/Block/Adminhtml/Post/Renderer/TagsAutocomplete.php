<?php

class DLS_DLSBlog_Block_Adminhtml_Post_Renderer_TagsAutocomplete extends Varien_Data_Form_Element_Text {

    protected $_element;

    public function getElementHtml() {

        $this->addClass('input-text');
        $html = '<input id="'.$this->getHtmlId().'" name="'.$this->getName()
             .'" value="'.$this->getEscapedValue().'" '.$this->serialize($this->getHtmlAttributes()).'/>'."\n";
        $html.= $this->getAfterElementHtml();

        $html .= Mage::app()->getLayout()
                ->createBlock('dls_dlsblog/adminhtml_form_renderer_tags')
                //->setData('element', $elementOptions)
                ->setTemplate('dls_dlsblog/form/renderer/tags-autocomplete.phtml')
                ->toHtml();
        return $html;
    }

}
