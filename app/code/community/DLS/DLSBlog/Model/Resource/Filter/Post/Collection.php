<?php

class DLS_DLSBlog_Model_Resource_Filter_Post_Collection extends DLS_DLSBlog_Model_Resource_Post_Collection {

    protected $_joinedFields = false;

    public function joinFields() {
        if (!$this->_joinedFields) {
            $this->getSelect()->join(
                    array('related' => $this->getTable('dls_dlsblog/filter_post')), 'related.post_id = e.entity_id', array('position')
            );
            $this->_joinedFields = true;
        }
        return $this;
    }

    public function addFilterFilter($filter) {
        if ($filter instanceof DLS_DLSBlog_Model_Filter) {
            $filter = $filter->getId();
        }
        if (!$this->_joinedFields) {
            $this->joinFields();
        }
        $this->getSelect()->where('related.filter_id = ?', $filter);
        return $this;
    }

}
