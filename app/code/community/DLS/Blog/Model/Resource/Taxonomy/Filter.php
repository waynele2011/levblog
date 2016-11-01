<?php

class DLS_Blog_Model_Resource_Taxonomy_Filter extends Mage_Core_Model_Resource_Db_Abstract {

    protected function _construct() {
        $this->_init('dls_blog/taxonomy_filter', 'rel_id');
    }

    public function saveTaxonomyRelation($taxonomy, $data) {
        if (!is_array($data)) {
            $data = array();
        }

        $adapter = $this->_getWriteAdapter();
        $bind = array(
            ':taxonomy_id' => (int) $taxonomy->getId(),
        );
        $select = $adapter->select()
                ->from($this->getMainTable(), array('rel_id', 'filter_id'))
                ->where('taxonomy_id = :taxonomy_id');

        $related = $adapter->fetchPairs($select, $bind);
        $deleteIds = array();
        foreach ($related as $relId => $filterId) {
            if (!isset($data[$filterId])) {
                $deleteIds[] = (int) $relId;
            }
        }
        if (!empty($deleteIds)) {
            $adapter->delete(
                    $this->getMainTable(), array('rel_id IN (?)' => $deleteIds)
            );
        }

        foreach ($data as $filterId => $info) {
            $adapter->insertOnDuplicate(
                    $this->getMainTable(), array(
                'taxonomy_id' => $taxonomy->getId(),
                'filter_id' => $filterId,
                'position' => @$info['position']
                    ), array('position')
            );
        }
        return $this;
    }

}
