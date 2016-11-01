<?php

class DLS_Blog_Block_Adminhtml_Blogset_Helper_Image extends Varien_Data_Form_Element_Image {

    protected function _getUrl() {
        $url = false;
        if ($this->getValue()) {
            $url = Mage::helper('dls_blog/blogset_image')->getImageBaseUrl() .
                    $this->getValue();
        }
        return $url;
    }

}
