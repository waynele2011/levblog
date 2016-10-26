<?php

class DLS_DLSBlog_Model_Resource_Post_Filter extends Mage_Core_Model_Resource_Db_Abstract {

    protected function _construct() {
        $this->_init('dls_dlsblog/post_filter', 'rel_id');
    }

    public function savePostRelation($post, $data) {
        if (!is_array($data)) {
            $data = array();
        }

        $adapter = $this->_getWriteAdapter();
        $bind = array(
            ':post_id' => (int) $post->getId(),
        );
        $select = $adapter->select()
                ->from($this->getMainTable(), array('rel_id', 'filter_id'))
                ->where('post_id = :post_id');

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
                'post_id' => $post->getId(),
                'filter_id' => $filterId,
                'position' => @$info['position']
                    ), array('position')
            );
        }
        return $this;
    }

}
