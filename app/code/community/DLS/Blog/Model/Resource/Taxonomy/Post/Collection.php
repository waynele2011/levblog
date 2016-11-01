<?php

class DLS_Blog_Model_Resource_Taxonomy_Post_Collection extends DLS_Blog_Model_Resource_Post_Collection {

    protected $_joinedFields = false;

    public function joinFields() {
        if (!$this->_joinedFields) {
            $this->getSelect()->join(
                    array('related' => $this->getTable('dls_blog/taxonomy_post')), 'related.post_id = e.entity_id', array('position')
            );
            $this->_joinedFields = true;
        }
        return $this;
    }

    public function addTaxonomyFilter($taxonomy) {
        if ($taxonomy instanceof DLS_Blog_Model_Taxonomy) {
            $taxonomy = $taxonomy->getId();
        }
        if (!$this->_joinedFields) {
            $this->joinFields();
        }
        $this->getSelect()->where('related.taxonomy_id = ?', $taxonomy);
        return $this;
    }

}
