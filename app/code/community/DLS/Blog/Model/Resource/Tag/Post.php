<?php

class DLS_Blog_Model_Resource_Tag_Post extends Mage_Core_Model_Resource_Db_Abstract {

    protected function _construct() {
        $this->_init('dls_blog/tag_post', 'rel_id');
    }

    public function saveTagRelation($tag, $data) {
        if (!is_array($data)) {
            $data = array();
        }

        $adapter = $this->_getWriteAdapter();
        $bind = array(
            ':tag_id' => (int) $tag->getId(),
        );
        $select = $adapter->select()
                ->from($this->getMainTable(), array('rel_id', 'post_id'))
                ->where('tag_id = :tag_id');

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
                'tag_id' => $tag->getId(),
                'post_id' => $postId,
                'position' => @$info['position']
                    ), array('position')
            );
        }
        return $this;
    }

}
