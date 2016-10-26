<?php

class DLS_DLSBlog_Model_Resource_Tag_Post_Collection extends DLS_DLSBlog_Model_Resource_Post_Collection {

    protected $_joinedFields = false;

    public function joinFields() {
        if (!$this->_joinedFields) {
            $this->getSelect()->join(
                    array('related' => $this->getTable('dls_dlsblog/tag_post')), 'related.post_id = e.entity_id', array('position')
            );
            $this->_joinedFields = true;
        }
        return $this;
    }

    public function addTagFilter($tag) {
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
