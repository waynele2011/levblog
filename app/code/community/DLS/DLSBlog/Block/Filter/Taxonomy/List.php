<?php

/**
 * Filter Taxonomies list block
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Filter_Taxonomy_List extends DLS_DLSBlog_Block_Taxonomy_List
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
        $filter = $this->getFilter();
         if ($filter) {
             $this->getTaxonomies()->addFilterFilter($filter->getId());
             $this->getTaxonomies()->unshiftOrder('related_filter.position', 'ASC');
         }
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return DLS_DLSBlog_Block_Filter_Taxonomy_List
     * @author Ultimate Module Creator
     */
    protected function _prepareLayout()
    {
        return $this;
    }

    /**
     * get the current filter
     *
     * @access public
     * @return DLS_DLSBlog_Model_Filter
     * @author Ultimate Module Creator
     */
    public function getFilter()
    {
        return Mage::registry('current_filter');
    }
}
