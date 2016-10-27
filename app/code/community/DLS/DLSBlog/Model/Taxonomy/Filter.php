<?php 

/**
 * Taxonomy filter model
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Model_Taxonomy_Filter extends Mage_Core_Model_Abstract
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
        $this->_init('dls_dlsblog/taxonomy_filter');
    }

    /**
     * Save data for taxonomy - filter relation
     * @access public
     * @param  DLS_DLSBlog_Model_Taxonomy $taxonomy
     * @return DLS_DLSBlog_Model_Taxonomy_Filter
     * @author Ultimate Module Creator
     */
    public function saveTaxonomyRelation($taxonomy)
    {
        $data = $taxonomy->getFiltersData();
        if (!is_null($data)) {
            $this->_getResource()->saveTaxonomyRelation($taxonomy, $data);
        }
        return $this;
    }

    /**
     * get  for taxonomy
     *
     * @access public
     * @param DLS_DLSBlog_Model_Taxonomy $taxonomy
     * @return DLS_DLSBlog_Model_Resource_Taxonomy_Filter_Collection
     * @author Ultimate Module Creator
     */
    public function getFiltersCollection($taxonomy)
    {
        $collection = Mage::getResourceModel('dls_dlsblog/taxonomy_filter_collection')
            ->addTaxonomyFilter($taxonomy);
        return $collection;
    }
}
