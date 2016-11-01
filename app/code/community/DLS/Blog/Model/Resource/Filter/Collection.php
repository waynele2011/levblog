<?php

class DLS_Blog_Model_Resource_Filter_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {

    protected $_joinedFields = array();

    protected function _construct() {
        parent::_construct();
        $this->_init('dls_blog/filter');
    }

    protected function _toOptionArray($valueField = 'entity_id', $labelField = 'name', $additional = array()) {
        return parent::_toOptionArray($valueField, $labelField, $additional);
    }

    protected function _toOptionHash($valueField = 'entity_id', $labelField = 'name') {
        return parent::_toOptionHash($valueField, $labelField);
    }

    public function addTaxonomyFilter($taxonomy) {
        if ($taxonomy instanceof DLS_Blog_Model_Taxonomy) {
            $taxonomy = $taxonomy->getId();
        }
        if (!isset($this->_joinedFields['taxonomy'])) {
            $this->getSelect()->join(
                    array('related_taxonomy' => $this->getTable('dls_blog/filter_taxonomy')), 'related_taxonomy.filter_id = main_table.entity_id', array('position')
            );
            $this->getSelect()->where('related_taxonomy.taxonomy_id = ?', $taxonomy);
            $this->_joinedFields['taxonomy'] = true;
        }
        return $this;
    }

    public function getSelectCountSql() {
        $countSelect = parent::getSelectCountSql();
        $countSelect->reset(Zend_Db_Select::GROUP);
        return $countSelect;
    }

}
