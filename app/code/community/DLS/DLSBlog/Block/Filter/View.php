<?php

class DLS_DLSBlog_Block_Filter_View extends Mage_Core_Block_Template {

    public function getCurrentFilter() {
        return Mage::registry('current_filter');
    }

}
