<?php

class DLS_Blog_Model_Resource_Taxonomy_Post extends Mage_Core_Model_Resource_Db_Abstract {

    protected function _construct() {
        $this->_init('dls_blog/taxonomy_post', 'rel_id');
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
                ->from($this->getMainTable(), array('rel_id', 'post_id'))
                ->where('taxonomy_id = :taxonomy_id');

        $related = $adapter->fetchPairs($select, $bind);
        $deleteIds = array();
        foreach ($related as $relId => $postId) {
            if (!isset($data[$postId])) {
                $deleteIds[] = (int) $relId;
            }
        }
        if (!empty($deleteIds)) {
            $adapter->delete(
                    $this->getMainTable(), array('rel_id IN (?)' => $deleteIds)
            );
        }

        foreach ($data as $postId => $info) {
            $adapter->insertOnDuplicate(
                    $this->getMainTable(), array(
                'taxonomy_id' => $taxonomy->getId(),
                'post_id' => $postId,
                'position' => @$info['position']
                    ), array('position')
            );
        }
        return $this;
    }

}
