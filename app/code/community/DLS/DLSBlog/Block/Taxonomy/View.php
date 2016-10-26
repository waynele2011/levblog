<?php

class DLS_DLSBlog_Block_Taxonomy_View extends Mage_Core_Block_Template {

    public function getCurrentTaxonomy() {
        return Mage::registry('current_taxonomy');
    }

}
