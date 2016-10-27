<?php

/**
 * Taxonomy view block
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Taxonomy_View extends Mage_Core_Block_Template
{
    /**
     * get the current taxonomy
     *
     * @access public
     * @return mixed (DLS_DLSBlog_Model_Taxonomy|null)
     * @author Ultimate Module Creator
     */
    public function getCurrentTaxonomy()
    {
        return Mage::registry('current_taxonomy');
    }
}
