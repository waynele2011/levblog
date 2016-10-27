<?php 

/**
 * Blog setting taxonomy model
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Model_Blogset_Taxonomy extends Mage_Core_Model_Abstract
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
        $this->_init('dls_dlsblog/blogset_taxonomy');
    }

    /**
     * Save data for blog setting - taxonomy relation
     * @access public
     * @param  DLS_DLSBlog_Model_Blogset $blogset
     * @return DLS_DLSBlog_Model_Blogset_Taxonomy
     * @author Ultimate Module Creator
     */
    public function saveBlogsetRelation($blogset)
    {
        $data = $blogset->getTaxonomiesData();
        if (!is_null($data)) {
            $this->_getResource()->saveBlogsetRelation($blogset, $data);
        }
        return $this;
    }

    /**
     * get  for blog setting
     *
     * @access public
     * @param DLS_DLSBlog_Model_Blogset $blogset
     * @return DLS_DLSBlog_Model_Resource_Blogset_Taxonomy_Collection
     * @author Ultimate Module Creator
     */
    public function getTaxonomiesCollection($blogset)
    {
        $collection = Mage::getResourceModel('dls_dlsblog/blogset_taxonomy_collection')
            ->addBlogsetFilter($blogset);
        return $collection;
    }
}
