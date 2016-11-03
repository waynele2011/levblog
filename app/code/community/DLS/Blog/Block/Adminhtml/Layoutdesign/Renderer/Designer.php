<?php

class DLS_Blog_Block_Adminhtml_Layoutdesign_Renderer_Designer extends Varien_Data_Form_Element_Textarea {

    protected $_element;

    public function getElementHtml() {

        $this->addClass('textarea');
        $html = '<textarea'
                . ' id="' . $this->getHtmlId() . '"'
                . ' name="' . $this->getName() . '"'
                . ' style="display:none;"'
                . '' . $this->serialize($this->getHtmlAttributes()) . ''
                . ' >';
        $html .= $this->getEscapedValue();
        $html .= "</textarea>";
        $html .= $this->getAfterElementHtml();

        $elementOptions = array(
            'id' => $this->getHtmlId(),
            'name' => $this->getName(),
            'html' => $this->serialize($this->getHtmlAttributes()),
            'value' => $this->getEscapedValue(),
            'after' => $this->getAfterElementHtml(),
        );
        $html .= Mage::app()->getLayout()
                ->createBlock('dls_blog/adminhtml_form_renderer_designframe')
                ->setData('element', $elementOptions)
                ->setTemplate('dls_blog/form/renderer/layout_designer.phtml')
                ->toHtml();
        return $html;
    }

}
