<?php

class DLS_DLSBlog_Block_Adminhtml_Post_Helper_Image extends Varien_Data_Form_Element_Image {

    protected function _getUrl() {
        $url = false;
        if ($this->getValue()) {
            $url = Mage::helper('dls_dlsblog/post_image')->getImageBaseUrl() .
                    $this->getValue();
        }
        return $url;
    }

}
