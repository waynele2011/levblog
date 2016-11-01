<?php

class DLS_Blog_Model_Resource_Blogset_Taxonomy_Collection extends DLS_Blog_Model_Resource_Taxonomy_Collection {

    protected $_joinedFields = false;

    public function joinFields() {
        if (!$this->_joinedFields) {
            $this->getSelect()->join(
                    array('related' => $this->getTable('dls_blog/blogset_taxonomy')), 'related.taxonomy_id = main_table.entity_id', array('position')
            );
            $this->_joinedFields = true;
        }
        return $this;
    }

    public function addBlogsetFilter($blogset) {
        if ($blogset instanceof DLS_Blog_Model_Blogset) {
            $blogset = $blogset->getId();
        }
        if (!$this->_joinedFields) {
            $this->joinFields();
        }
        $this->getSelect()->where('related.blogset_id = ?', $blogset);
        return $this;
    }

}
