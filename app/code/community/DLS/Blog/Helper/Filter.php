<?php

class DLS_Blog_Helper_Filter extends Mage_Core_Helper_Abstract {

    public function getUseBreadcrumbs() {
        return Mage::getStoreConfigFlag('dls_blog/filter/breadcrumbs');
    }

}
