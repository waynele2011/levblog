<?php

class DLS_DLSBlog_Helper_Filter extends Mage_Core_Helper_Abstract {

    public function getUseBreadcrumbs() {
        return Mage::getStoreConfigFlag('dls_dlsblog/filter/breadcrumbs');
    }

}
