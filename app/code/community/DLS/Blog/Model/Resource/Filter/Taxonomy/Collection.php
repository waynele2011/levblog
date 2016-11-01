<?php

class DLS_Blog_Model_Resource_Filter_Taxonomy_Collection extends DLS_Blog_Model_Resource_Taxonomy_Collection {

    protected $_joinedFields = false;

    public function joinFields() {
        if (!$this->_joinedFields) {
            $this->getSelect()->join(
                    array('related' => $this->getTable('dls_blog/filter_taxonomy')), 'related.taxonomy_id = main_table.entity_id', array('position')
            );
            $this->_joinedFields = true;
        }
        return $this;
    }

    public function addFilterFilter($filter) {
        if ($filter instanceof DLS_Blog_Model_Filter) {
            $filter = $filter->getId();
        }
        if (!$this->_joinedFields) {
            $this->joinFields();
        }
        $this->getSelect()->where('related.filter_id = ?', $filter);
        return $this;
    }

}
