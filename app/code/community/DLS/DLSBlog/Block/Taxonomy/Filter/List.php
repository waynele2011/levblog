<?php

/**
 * Category Filters list block
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Taxonomy_Filter_List extends DLS_DLSBlog_Block_Filter_List
{
    /**
     * initialize
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $taxonomy = $this->getTaxonomy();
         if ($taxonomy) {
             $this->getFilters()->addTaxonomyFilter($taxonomy->getId());
             $this->getFilters()->unshiftOrder('related_taxonomy.position', 'ASC');
         }
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return DLS_DLSBlog_Block_Taxonomy_Filter_List
     * @author Ultimate Module Creator
     */
    protected function _prepareLayout()
    {
        return $this;
    }

    /**
     * get the current category
     *
     * @access public
     * @return DLS_DLSBlog_Model_Taxonomy
     * @author Ultimate Module Creator
     */
    public function getTaxonomy()
    {
        return Mage::registry('current_taxonomy');
    }
}
