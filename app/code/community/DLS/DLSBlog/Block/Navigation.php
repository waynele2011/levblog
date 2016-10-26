<?php

class DLS_DLSBlog_Block_Navigation extends Mage_Catalog_Block_Navigation {

//    public function renderCategoriesMenuHtml($level = 0, $outermostItemClass = '', $childrenWrapClass = '') {
//
//        // @TODO
//        // if route is active
//        $active = ($this->getRequest()->getRouteName() == 'dls_dlsblog' ? 'active' : '');
//
//        // Get navigation menu html
//        $html = parent::renderCategoriesMenuHtml($level, $outermostItemClass, $childrenWrapClass);
//
//        // if module is active
//        if (Mage::helper('core/data')->isModuleEnabled('DLS_DLSBlog')) {
//            // Adding new menu item. You can also add few items or child elements there
//            $html .= "<li class='$outermostItemClass $active'><a class='$outermostItemClass' href='"
//                    . Mage::getUrl('dls_dlsblog') . "'><span>"
//                    . Mage::helper('dls_dlsblog')->__('New Menu Item') . "</span></a></li>";
//        }
//        return $html;
//    }

}
