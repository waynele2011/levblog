<?php 

/**
 * Tag post model
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Model_Tag_Post extends Mage_Core_Model_Abstract
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
        $this->_init('dls_dlsblog/tag_post');
    }

    /**
     * Save data for tag - post relation
     * @access public
     * @param  DLS_DLSBlog_Model_Tag $tag
     * @return DLS_DLSBlog_Model_Tag_Post
     * @author Ultimate Module Creator
     */
    public function saveTagRelation($tag)
    {
        $data = $tag->getPostsData();
        if (!is_null($data)) {
            $this->_getResource()->saveTagRelation($tag, $data);
        }
        return $this;
    }

    /**
     * get  for tag
     *
     * @access public
     * @param DLS_DLSBlog_Model_Tag $tag
     * @return DLS_DLSBlog_Model_Resource_Tag_Post_Collection
     * @author Ultimate Module Creator
     */
    public function getPostsCollection($tag)
    {
        $collection = Mage::getResourceModel('dls_dlsblog/tag_post_collection')
            ->addTagFilter($tag);
        return $collection;
    }
}
