<?php

class DLS_DLSBlog_Model_Resource_Filter_Post extends Mage_Core_Model_Resource_Db_Abstract {

    protected function _construct() {
        $this->_init('dls_dlsblog/filter_post', 'rel_id');
    }

    public function saveFilterRelation($filter, $data) {
        if (!is_array($data)) {
            $data = array();
        }

        $adapter = $this->_getWriteAdapter();
        $bind = array(
            ':filter_id' => (int) $filter->getId(),
        );
        $select = $adapter->select()
                ->from($this->getMainTable(), array('rel_id', 'post_id'))
                ->where('filter_id = :filter_id');

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
                'filter_id' => $filter->getId(),
                'post_id' => $postId,
                'position' => @$info['position']
                    ), array('position')
            );
        }
        return $this;
    }

}
