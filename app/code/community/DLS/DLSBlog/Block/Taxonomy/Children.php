<?php

/**
 * Taxonomy children list block
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Taxonomy_Children extends DLS_DLSBlog_Block_Taxonomy_List
{
    /**
     * prepare the layout
     *
     * @access protected
     * @return DLS_DLSBlog_Block_Taxonomy_Children
     * @author Ultimate Module Creator
     */
    protected function _prepareLayout()
    {
        $this->getTaxonomies()->addFieldToFilter('parent_id', $this->getCurrentTaxonomy()->getId());
        return $this;
    }

    /**
     * get the current taxonomy
     *
     * @access protected
     * @return DLS_DLSBlog_Model_Taxonomy
     * @author Ultimate Module Creator
     */
    public function getCurrentTaxonomy()
    {
        return Mage::registry('current_taxonomy');
    }
}
