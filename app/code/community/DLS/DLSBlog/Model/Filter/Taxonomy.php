<?php 

/**
 * Filter taxonomy model
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Model_Filter_Taxonomy extends Mage_Core_Model_Abstract
{
    /**
     * Initialize resource
     *
     * @access protected
     * @return void
     * @author Ultimate Module Creator
     */
    protected function _construct()
    {
        $this->_init('dls_dlsblog/filter_taxonomy');
    }

    /**
     * Save data for filter - taxonomy relation
     * @access public
     * @param  DLS_DLSBlog_Model_Filter $filter
     * @return DLS_DLSBlog_Model_Filter_Taxonomy
     * @author Ultimate Module Creator
     */
    public function saveFilterRelation($filter)
    {
        $data = $filter->getTaxonomiesData();
        if (!is_null($data)) {
            $this->_getResource()->saveFilterRelation($filter, $data);
        }
        return $this;
    }

    /**
     * get  for filter
     *
     * @access public
     * @param DLS_DLSBlog_Model_Filter $filter
     * @return DLS_DLSBlog_Model_Resource_Filter_Taxonomy_Collection
     * @author Ultimate Module Creator
     */
    public function getTaxonomiesCollection($filter)
    {
        $collection = Mage::getResourceModel('dls_dlsblog/filter_taxonomy_collection')
            ->addFilterFilter($filter);
        return $collection;
    }
}
