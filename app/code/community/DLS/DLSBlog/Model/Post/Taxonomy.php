<?php 

/**
 * Post taxonomy model
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Model_Post_Taxonomy extends Mage_Core_Model_Abstract
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
        $this->_init('dls_dlsblog/post_taxonomy');
    }

    /**
     * Save data for post - taxonomy relation
     * @access public
     * @param  DLS_DLSBlog_Model_Post $post
     * @return DLS_DLSBlog_Model_Post_Taxonomy
     * @author Ultimate Module Creator
     */
    public function savePostRelation($post)
    {
        $data = $post->getTaxonomiesData();
        if (!is_null($data)) {
            $this->_getResource()->savePostRelation($post, $data);
        }
        return $this;
    }

    /**
     * get  for post
     *
     * @access public
     * @param DLS_DLSBlog_Model_Post $post
     * @return DLS_DLSBlog_Model_Resource_Post_Taxonomy_Collection
     * @author Ultimate Module Creator
     */
    public function getTaxonomiesCollection($post)
    {
        $collection = Mage::getResourceModel('dls_dlsblog/post_taxonomy_collection')
            ->addPostFilter($post);
        return $collection;
    }
}
