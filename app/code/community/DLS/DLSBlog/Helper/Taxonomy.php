<?php

class DLS_DLSBlog_Helper_Taxonomy extends Mage_Core_Helper_Abstract {

    public function getUseBreadcrumbs() {
        return Mage::getStoreConfigFlag('dls_dlsblog/taxonomy/breadcrumbs');
    }

    const TAXONOMY_ROOT_ID = 1;

    public function getRootTaxonomyId() {
        return self::TAXONOMY_ROOT_ID;
    }

}
