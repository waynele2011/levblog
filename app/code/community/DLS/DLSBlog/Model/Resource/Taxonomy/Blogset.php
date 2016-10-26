<?php

class DLS_DLSBlog_Model_Resource_Taxonomy_Blogset extends Mage_Core_Model_Resource_Db_Abstract {

    protected function _construct() {
        $this->_init('dls_dlsblog/taxonomy_blogset', 'rel_id');
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
                ->from($this->getMainTable(), array('rel_id', 'blogset_id'))
                ->where('taxonomy_id = :taxonomy_id');

        $related = $adapter->fetchPairs($select, $bind);
        $deleteIds = array();
        foreach ($related as $relId => $blogsetId) {
            if (!isset($data[$blogsetId])) {
                $deleteIds[] = (int) $relId;
            }
        }
        if (!empty($deleteIds)) {
            $adapter->delete(
                    $this->getMainTable(), array('rel_id IN (?)' => $deleteIds)
            );
        }

        foreach ($data as $blogsetId => $info) {
            $adapter->insertOnDuplicate(
                    $this->getMainTable(), array(
                'taxonomy_id' => $taxonomy->getId(),
                'blogset_id' => $blogsetId,
                'position' => @$info['position']
                    ), array('position')
            );
        }
        return $this;
    }

}
