<?php

class DLS_DLSBlog_Block_Adminhtml_Form_Renderer_Designframe extends Mage_Core_Block_Template {

    protected function _construct() {
        parent::_construct();
    }

    public function items_html($identifiers, $position) {
        $html = '';
        if (count($identifiers) > 0) {
            foreach ($identifiers as $identifier) {
                $block_item = Mage::getModel('cms/block')->getCollection()->addFieldToFilter('identifier', array('eq' => $identifier))->getFirstItem();
                $html .= "
                        <li class=\"block-item block-item-listed\"
                            title=\"{$block_item->getTitle()}\"
                            identifier=\"{$block_item->getIdentifier()}\"
                            block_id=\"{$block_item->getId()}\">
                                {$block_item->getTitle()}
                                <input type=\"hidden\" 
                                        name=\"layoutdesign[design_frame][$position][]\" 
                                        value=\"{$block_item->getIdentifier()}\"/>
                                            <a class='bt-remove-block'>X</a>
                        </li>
                ";
            }
        }
        return $html;
    }

    public function getBlockCollection() {
        $block_model = Mage::getModel('cms/block');
        $block_collection = $block_model->getCollection()->addStoreFilter(Mage::app()->getStore()->getId(), true);
        return $block_collection;
    }

    public function getLayoutDesignCode() {
        if (Mage::registry('current_layoutdesign') && Mage::registry('current_layoutdesign')->getId()) {
            $layout_design = Mage::registry('current_layoutdesign')->getData();
            $_design_code = Mage::helper('core')->jsonDecode($layout_design['design_code']);
        }
        else{
            $_design_code = '';
        }
        return $_design_code;
    }

}
