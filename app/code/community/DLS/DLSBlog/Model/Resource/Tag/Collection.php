<?php

class DLS_DLSBlog_Model_Resource_Tag_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {

    protected $_joinedFields = array();

    protected function _construct() {
        parent::_construct();
        $this->_init('dls_dlsblog/tag');
    }

    protected function _toOptionArray($valueField = 'entity_id', $labelField = 'name', $additional = array()) {
        return parent::_toOptionArray($valueField, $labelField, $additional);
    }

    protected function _toOptionHash($valueField = 'entity_id', $labelField = 'name') {
        return parent::_toOptionHash($valueField, $labelField);
    }

    public function addPostFilter($post) {
        if ($post instanceof DLS_DLSBlog_Model_Post) {
            $post = $post->getId();
        }
        if (!isset($this->_joinedFields['post'])) {
            $this->getSelect()->join(
                    array('related_post' => $this->getTable('dls_dlsblog/tag_post')), 'related_post.tag_id = main_table.entity_id', array('position')
            );
            $this->getSelect()->where('related_post.post_id = ?', $post);
            $this->_joinedFields['post'] = true;
        }
        return $this;
    }

    public function getSelectCountSql() {
        $countSelect = parent::getSelectCountSql();
        $countSelect->reset(Zend_Db_Select::GROUP);
        return $countSelect;
    }

}
