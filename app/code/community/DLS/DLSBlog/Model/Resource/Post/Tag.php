<?php

class DLS_DLSBlog_Model_Resource_Post_Tag extends Mage_Core_Model_Resource_Db_Abstract {

    protected function _construct() {
        $this->_init('dls_dlsblog/post_tag', 'rel_id');
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
                ->from($this->getMainTable(), array('rel_id', 'tag_id'))
                ->where('post_id = :post_id');

        $related = $adapter->fetchPairs($select, $bind);
        $deleteIds = array();
        foreach ($related as $relId => $tagId) {
            if (!isset($data[$tagId])) {
                $deleteIds[] = (int) $relId;
            }
        }
        if (!empty($deleteIds)) {
            $adapter->delete(
                    $this->getMainTable(), array('rel_id IN (?)' => $deleteIds)
            );
        }

        foreach ($data as $tagId => $info) {
            $adapter->insertOnDuplicate(
                    $this->getMainTable(), array(
                'post_id' => $post->getId(),
                'tag_id' => $tagId,
                'position' => @$info['position']
                    ), array('position')
            );
        }
        return $this;
    }

}
