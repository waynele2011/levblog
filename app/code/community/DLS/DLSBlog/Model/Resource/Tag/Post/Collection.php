<?php

/**
 * Tag - Post relation resource model collection
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Model_Resource_Tag_Post_Collection extends DLS_DLSBlog_Model_Resource_Post_Collection
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
     * @return DLS_DLSBlog_Model_Resource_Tag_Post_Collection
     * @author Ultimate Module Creator
     */
    public function joinFields()
    {
        if (!$this->_joinedFields) {
            $this->getSelect()->join(
                array('related' => $this->getTable('dls_dlsblog/tag_post')),
                'related.post_id = e.entity_id',
                array('position')
            );
            $this->_joinedFields = true;
        }
        return $this;
    }

    /**
     * add tag filter
     *
     * @access public
     * @param DLS_DLSBlog_Model_Tag | int $tag
     * @return DLS_DLSBlog_Model_Resource_Tag_Post_Collection
     * @author Ultimate Module Creator
     */
    public function addTagFilter($tag)
    {
        if ($tag instanceof DLS_DLSBlog_Model_Tag) {
            $tag = $tag->getId();
        }
        if (!$this->_joinedFields) {
            $this->joinFields();
        }
        $this->getSelect()->where('related.tag_id = ?', $tag);
        return $this;
    }
}
