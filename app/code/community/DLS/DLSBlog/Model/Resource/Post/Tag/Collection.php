<?php

/**
 * Post - Tag relation resource model collection
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Model_Resource_Post_Tag_Collection extends DLS_DLSBlog_Model_Resource_Tag_Collection
{
    /**
     * remember if fields have been joined
     * @var bool
     */
    protected $_joinedFields = false;

    /**
     * join the link table
     *
     * @access public
     * @return DLS_DLSBlog_Model_Resource_Post_Tag_Collection
     * @author Ultimate Module Creator
     */
    public function joinFields()
    {
        if (!$this->_joinedFields) {
            $this->getSelect()->join(
                array('related' => $this->getTable('dls_dlsblog/post_tag')),
                'related.tag_id = main_table.entity_id',
                array('position')
            );
            $this->_joinedFields = true;
        }
        return $this;
    }

    /**
     * add post filter
     *
     * @access public
     * @param DLS_DLSBlog_Model_Post | int $post
     * @return DLS_DLSBlog_Model_Resource_Post_Tag_Collection
     * @author Ultimate Module Creator
     */
    public function addPostFilter($post)
    {
        if ($post instanceof DLS_DLSBlog_Model_Post) {
            $post = $post->getId();
        }
        if (!$this->_joinedFields) {
            $this->joinFields();
        }
        $this->getSelect()->where('related.post_id = ?', $post);
        return $this;
    }
}
