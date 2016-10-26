<?php 

/**
 * Post - Tag relation model
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Model_Resource_Post_Tag extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * initialize resource model
     *
     * @access protected
     * @return void
     * @see Mage_Core_Model_Resource_Abstract::_construct()
     * @author Ultimate Module Creator
     */
    protected function  _construct()
    {
        $this->_init('dls_dlsblog/post_tag', 'rel_id');
    }

    /**
     * Save post - tag relations
     *
     * @access public
     * @param DLS_DLSBlog_Model_Post $post
     * @param array $data
     * @return DLS_DLSBlog_Model_Resource_Post_Tag
     * @author Ultimate Module Creator
     */
    public function savePostRelation($post, $data)
    {
        if (!is_array($data)) {
            $data = array();
        }

        $adapter = $this->_getWriteAdapter();
        $bind    = array(
            ':post_id'    => (int)$post->getId(),
        );
        $select = $adapter->select()
            ->from($this->getMainTable(), array('rel_id', 'tag_id'))
            ->where('post_id = :post_id');

        $related   = $adapter->fetchPairs($select, $bind);
        $deleteIds = array();
        foreach ($related as $relId => $tagId) {
            if (!isset($data[$tagId])) {
                $deleteIds[] = (int)$relId;
            }
        }
        if (!empty($deleteIds)) {
            $adapter->delete(
                $this->getMainTable(),
                array('rel_id IN (?)' => $deleteIds)
            );
        }

        foreach ($data as $tagId => $info) {
            $adapter->insertOnDuplicate(
                $this->getMainTable(),
                array(
                    'post_id'      => $post->getId(),
                    'tag_id'     => $tagId,
                    'position'      => @$info['position']
                ),
                array('position')
            );
        }
        return $this;
    }
}
