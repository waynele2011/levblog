<?php 

/**
 * Post tag model
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Model_Post_Tag extends Mage_Core_Model_Abstract
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
        $this->_init('dls_dlsblog/post_tag');
    }

    /**
     * Save data for post - tag relation
     * @access public
     * @param  DLS_DLSBlog_Model_Post $post
     * @return DLS_DLSBlog_Model_Post_Tag
     * @author Ultimate Module Creator
     */
    public function savePostRelation($post)
    {
        $data = $post->getTagsData();
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
     * @return DLS_DLSBlog_Model_Resource_Post_Tag_Collection
     * @author Ultimate Module Creator
     */
    public function getTagsCollection($post)
    {
        $collection = Mage::getResourceModel('dls_dlsblog/post_tag_collection')
            ->addPostFilter($post);
        return $collection;
    }
}
