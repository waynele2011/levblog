<?php

class DLS_Blog_Model_Resource_Post_Taxonomy_Collection extends DLS_Blog_Model_Resource_Taxonomy_Collection {

    protected $_joinedFields = false;

    public function joinFields() {
        if (!$this->_joinedFields) {
            $this->getSelect()->join(
                    array('related' => $this->getTable('dls_blog/post_taxonomy')), 'related.taxonomy_id = main_table.entity_id', array('position')
            );
            $this->_joinedFields = true;
        }
        return $this;
    }

    public function addPostFilter($post) {
        if ($post instanceof DLS_Blog_Model_Post) {
            $post = $post->getId();
        }
        if (!$this->_joinedFields) {
            $this->joinFields();
        }
        $this->getSelect()->where('related.post_id = ?', $post);
        return $this;
    }

}
