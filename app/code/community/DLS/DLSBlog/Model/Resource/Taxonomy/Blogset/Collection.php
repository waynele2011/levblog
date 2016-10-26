<?php

class DLS_DLSBlog_Model_Resource_Taxonomy_Blogset_Collection extends DLS_DLSBlog_Model_Resource_Blogset_Collection {

    protected $_joinedFields = false;

    public function joinFields() {
        if (!$this->_joinedFields) {
            $this->getSelect()->join(
                    array('related' => $this->getTable('dls_dlsblog/taxonomy_blogset')), 'related.blogset_id = main_table.entity_id', array('position')
            );
            $this->_joinedFields = true;
        }
        return $this;
    }

    public function addTaxonomyFilter($taxonomy) {
        if ($taxonomy instanceof DLS_DLSBlog_Model_Taxonomy) {
            $taxonomy = $taxonomy->getId();
        }
        if (!$this->_joinedFields) {
            $this->joinFields();
        }
        $this->getSelect()->where('related.taxonomy_id = ?', $taxonomy);
        return $this;
    }

}
