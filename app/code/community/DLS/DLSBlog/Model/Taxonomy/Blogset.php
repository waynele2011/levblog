<?php 

/**
 * Taxonomy blog setting model
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Model_Taxonomy_Blogset extends Mage_Core_Model_Abstract
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
        $this->_init('dls_dlsblog/taxonomy_blogset');
    }

    /**
     * Save data for taxonomy - blog setting relation
     * @access public
     * @param  DLS_DLSBlog_Model_Taxonomy $taxonomy
     * @return DLS_DLSBlog_Model_Taxonomy_Blogset
     * @author Ultimate Module Creator
     */
    public function saveTaxonomyRelation($taxonomy)
    {
        $data = $taxonomy->getBlogsetsData();
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
     * @return DLS_DLSBlog_Model_Resource_Taxonomy_Blogset_Collection
     * @author Ultimate Module Creator
     */
    public function getBlogsetsCollection($taxonomy)
    {
        $collection = Mage::getResourceModel('dls_dlsblog/taxonomy_blogset_collection')
            ->addTaxonomyFilter($taxonomy);
        return $collection;
    }
}
