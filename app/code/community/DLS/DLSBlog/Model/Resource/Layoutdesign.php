<?php

/**
 * Layout design resource model
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Model_Resource_Layoutdesign extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Layout design tree object
     * @var Varien_Data_Tree_Db
     */
    protected $_tree;

    /**
     * constructor
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function _construct()
    {
        $this->_init('dls_dlsblog/layoutdesign', 'entity_id');
    }

    /**
     * Retrieve layout design tree object
     *
     * @access protected
     * @return Varien_Data_Tree_Db
     * @author Ultimate Module Creator
     */
    protected function _getTree()
    {
        if (!$this->_tree) {
            $this->_tree = Mage::getResourceModel('dls_dlsblog/layoutdesign_tree')->load();
        }
        return $this->_tree;
    }

    /**
     * Process layout design data before delete
     * update children count for parent layout design
     * delete child layout designs
     *
     * @access protected
     * @param Varien_Object $object
     * @return DLS_DLSBlog_Model_Resource_Layoutdesign
     * @author Ultimate Module Creator
     */
    protected function _beforeDelete(Mage_Core_Model_Abstract $object)
    {
        parent::_beforeDelete($object);
        /**
         * Update children count for all parent layout designs
         */
        $parentIds = $object->getParentIds();
        if ($parentIds) {
            $childDecrease = $object->getChildrenCount() + 1; // +1 is itself
            $data = array('children_count' => new Zend_Db_Expr('children_count - ' . $childDecrease));
            $where = array('entity_id IN(?)' => $parentIds);
            $this->_getWriteAdapter()->update($this->getMainTable(), $data, $where);
        }
        $this->deleteChildren($object);
        return $this;
    }

    /**
     * Delete children layout designs of specific layout design
     *
     * @access public
     * @param Varien_Object $object
     * @return DLS_DLSBlog_Model_Resource_Layoutdesign
     * @author Ultimate Module Creator
     */
    public function deleteChildren(Varien_Object $object)
    {
        $adapter = $this->_getWriteAdapter();
        $pathField = $adapter->quoteIdentifier('path');
        $select = $adapter->select()
            ->from($this->getMainTable(), array('entity_id'))
            ->where($pathField . ' LIKE :c_path');
        $childrenIds = $adapter->fetchCol($select, array('c_path' => $object->getPath() . '/%'));
        if (!empty($childrenIds)) {
            $adapter->delete(
                $this->getMainTable(),
                array('entity_id IN (?)' => $childrenIds)
            );
        }
        /**
         * Add deleted children ids to object
         * This data can be used in after delete event
         */
        $object->setDeletedChildrenIds($childrenIds);
        return $this;
    }

    /**
     * Process layout design data after save layout design object
     *
     * @access protected
     * @param Varien_Object $object
     * @return DLS_DLSBlog_Model_Resource_Layoutdesign
     * @author Ultimate Module Creator
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        if (substr($object->getPath(), -1) == '/') {
            $object->setPath($object->getPath() . $object->getId());
            $this->_savePath($object);
        }


        return parent::_afterSave($object);
    }

    /**
     * Update path field
     *
     * @access protected
     * @param DLS_DLSBlog_Model_Layoutdesign $object
     * @return DLS_DLSBlog_Model_Resource_Layoutdesign
     * @author Ultimate Module Creator
     */
    protected function _savePath($object)
    {
        if ($object->getId()) {
            $this->_getWriteAdapter()->update(
                $this->getMainTable(),
                array('path' => $object->getPath()),
                array('entity_id = ?' => $object->getId())
            );
        }
        return $this;
    }

    /**
     * Get maximum position of child layout designs by specific tree path
     *
     * @access protected
     * @param string $path
     * @return int
     * @author Ultimate Module Creator
     */
    protected function _getMaxPosition($path)
    {
        $adapter = $this->getReadConnection();
        $positionField = $adapter->quoteIdentifier('position');
        $level   = count(explode('/', $path));
        $bind = array(
            'c_level' => $level,
            'c_path'  => $path . '/%'
        );
        $select  = $adapter->select()
            ->from($this->getMainTable(), 'MAX(' . $positionField . ')')
            ->where($adapter->quoteIdentifier('path') . ' LIKE :c_path')
            ->where($adapter->quoteIdentifier('level') . ' = :c_level');

        $position = $adapter->fetchOne($select, $bind);
        if (!$position) {
            $position = 0;
        }
        return $position;
    }

    /**
     * Get children layout designs count
     *
     * @access public
     * @param int $layoutdesignId
     * @return int
     * @author Ultimate Module Creator
     */
    public function getChildrenCount($layoutdesignId)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getMainTable(), 'children_count')
            ->where('entity_id = :entity_id');
        $bind = array('entity_id' => $layoutdesignId);
        return $this->_getReadAdapter()->fetchOne($select, $bind);
    }

    /**
     * Check if layout design id exist
     *
     * @access public
     * @param int $entityId
     * @return bool
     * @author Ultimate Module Creator
     */
    public function checkId($entityId)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getMainTable(), 'entity_id')
            ->where('entity_id = :entity_id');
        $bind =  array('entity_id' => $entityId);
        return $this->_getReadAdapter()->fetchOne($select, $bind);
    }

    /**
     * Check array of layout designs identifiers
     *
     * @access public
     * @param array $ids
     * @return array
     * @author Ultimate Module Creator
     */
    public function verifyIds(array $ids)
    {
        if (empty($ids)) {
            return array();
        }
        $select = $this->_getReadAdapter()->select()
            ->from($this->getMainTable(), 'entity_id')
            ->where('entity_id IN(?)', $ids);

        return $this->_getReadAdapter()->fetchCol($select);
    }

    /**
     * Get count of active/not active children layout designs
     *
     * @param DLS_DLSBlog_Model_Layoutdesign $layoutdesign
     * @param bool $isActiveFlag
     * @return int
     * @author Ultimate Module Creator
     */
    public function getChildrenAmount($layoutdesign, $isActiveFlag = true)
    {
        $bind = array(
            'active_flag'  => $isActiveFlag,
            'c_path'   => $layoutdesign->getPath() . '/%'
        );
        $select = $this->_getReadAdapter()->select()
            ->from(array('m' => $this->getMainTable()), array('COUNT(m.entity_id)'))
            ->where('m.path LIKE :c_path')
            ->where('status' . ' = :active_flag');
        return $this->_getReadAdapter()->fetchOne($select, $bind);
    }

    /**
     * Return parent layout designs of layout design
     *
     * @access public
     * @param DLS_DLSBlog_Model_Layoutdesign $layoutdesign
     * @return array
     * @author Ultimate Module Creator
     */
    public function getParentLayoutdesigns($layoutdesign)
    {
        $pathIds = array_reverse(explode('/', $layoutdesign->getPath()));
        $layoutdesigns = Mage::getResourceModel('dls_dlsblog/layoutdesign_collection')
            ->addFieldToFilter('entity_id', array('in' => $pathIds))
            ->load()
            ->getItems();
        return $layoutdesigns;
    }

    /**
     * Return child layout designs
     *
     * @access public
     * @param DLS_DLSBlog_Model_Layoutdesign $layoutdesign
     * @return DLS_DLSBlog_Model_Resource_Layoutdesign_Collection
     * @author Ultimate Module Creator
     */
    public function getChildrenLayoutdesigns($layoutdesign)
    {
        $collection = $layoutdesign->getCollection();
        $collection
            ->addIdFilter($layoutdesign->getChildLayoutdesigns())
            ->setOrder('position', Varien_Db_Select::SQL_ASC)
            ->load();
        return $collection;
    }
    /**
     * Return children ids of layout design
     *
     * @access public
     * @param DLS_DLSBlog_Model_Layoutdesign $layoutdesign
     * @param boolean $recursive
     * @return array
     * @author Ultimate Module Creator
     */
    public function getChildren($layoutdesign, $recursive = true)
    {
        $bind = array(
            'c_path'   => $layoutdesign->getPath() . '/%'
        );
        $select = $this->_getReadAdapter()->select()
            ->from(array('m' => $this->getMainTable()), 'entity_id')
            ->where('status = ?', 1)
            ->where($this->_getReadAdapter()->quoteIdentifier('path') . ' LIKE :c_path');
        if (!$recursive) {
            $select->where($this->_getReadAdapter()->quoteIdentifier('level') . ' <= :c_level');
            $bind['c_level'] = $layoutdesign->getLevel() + 1;
        }
        return $this->_getReadAdapter()->fetchCol($select, $bind);
    }

    /**
     * Process layout design data before saving
     * prepare path and increment children count for parent layout designs
     *
     * @access protected
     * @param Varien_Object $object
     * @return DLS_DLSBlog_Model_Resource_Layoutdesign
     * @author Ultimate Module Creator
     */
    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        parent::_beforeSave($object);
        if (!$object->getChildrenCount()) {
            $object->setChildrenCount(0);
        }
        if ($object->getLevel() === null) {
            $object->setLevel(1);
        }
        if (!$object->getId() && !$object->getInitialSetupFlag()) {
            $object->setPosition($this->_getMaxPosition($object->getPath()) + 1);
            $path  = explode('/', $object->getPath());
            $level = count($path);
            $object->setLevel($level);
            if ($level) {
                $object->setParentId($path[$level - 1]);
            }
            $object->setPath($object->getPath() . '/');
            $toUpdateChild = explode('/', $object->getPath());
            $this->_getWriteAdapter()->update(
                $this->getMainTable(),
                array('children_count'  => new Zend_Db_Expr('children_count+1')),
                array('entity_id IN(?)' => $toUpdateChild)
            );
        }
        return $this;
    }

    /**
     * Retrieve layout designs
     *
     * @access public
     * @param integer $parent
     * @param integer $recursionLevel
     * @param boolean|string $sorted
     * @param boolean $asCollection
     * @param boolean $toLoad
     * @return Varien_Data_Tree_Node_Collection|DLS_DLSBlog_Model_Resource_Layoutdesign_Collection
     * @author Ultimate Module Creator
     */
    public function getLayoutdesigns(
        $parent,
        $recursionLevel = 0,
        $sorted = false,
        $asCollection = false,
        $toLoad = true
    )
    {
        $tree = Mage::getResourceModel('dls_dlsblog/layoutdesign_tree');
        $nodes = $tree->loadNode($parent)
            ->loadChildren($recursionLevel)
            ->getChildren();
        $tree->addCollectionData(null, $sorted, $parent, $toLoad, true);
        if ($asCollection) {
            return $tree->getCollection();
        }
        return $nodes;
    }

    /**
     * Return all children ids of layoutdesign (with layoutdesign id)
     *
     * @access public
     * @param DLS_DLSBlog_Model_Layoutdesign $layoutdesign
     * @return array
     * @author Ultimate Module Creator
     */
    public function getAllChildren($layoutdesign)
    {
        $children = $this->getChildren($layoutdesign);
        $myId = array($layoutdesign->getId());
        $children = array_merge($myId, $children);
        return $children;
    }

    /**
     * Check layout design is forbidden to delete.
     *
     * @access public
     * @param integer $layoutdesignId
     * @return boolean
     * @author Ultimate Module Creator
     */
    public function isForbiddenToDelete($layoutdesignId)
    {
        return ($layoutdesignId == Mage::helper('dls_dlsblog/layoutdesign')->getRootLayoutdesignId());
    }

    /**
     * Get layout design path value by its id
     *
     * @access public
     * @param int $layoutdesignId
     * @return string
     * @author Ultimate Module Creator
     */
    public function getLayoutdesignPathById($layoutdesignId)
    {
        $select = $this->getReadConnection()->select()
            ->from($this->getMainTable(), array('path'))
            ->where('entity_id = :entity_id');
        $bind = array('entity_id' => (int)$layoutdesignId);
        return $this->getReadConnection()->fetchOne($select, $bind);
    }

    /**
     * Move layout design to another parent node
     *
     * @access public
     * @param DLS_DLSBlog_Model_Layoutdesign $layoutdesign
     * @param DLS_DLSBlog_Model_Layoutdesign $newParent
     * @param null|int $afterLayoutdesignId
     * @return DLS_DLSBlog_Model_Resource_Layoutdesign
     * @author Ultimate Module Creator
     */
    public function changeParent(
        DLS_DLSBlog_Model_Layoutdesign $layoutdesign,
        DLS_DLSBlog_Model_Layoutdesign $newParent,
        $afterLayoutdesignId = null
    )
    {
        $childrenCount  = $this->getChildrenCount($layoutdesign->getId()) + 1;
        $table          = $this->getMainTable();
        $adapter        = $this->_getWriteAdapter();
        $levelFiled     = $adapter->quoteIdentifier('level');
        $pathField      = $adapter->quoteIdentifier('path');

        /**
         * Decrease children count for all old layout design parent layout designs
         */
        $adapter->update(
            $table,
            array('children_count' => new Zend_Db_Expr('children_count - ' . $childrenCount)),
            array('entity_id IN(?)' => $layoutdesign->getParentIds())
        );
        /**
         * Increase children count for new layout design parents
         */
        $adapter->update(
            $table,
            array('children_count' => new Zend_Db_Expr('children_count + ' . $childrenCount)),
            array('entity_id IN(?)' => $newParent->getPathIds())
        );

        $position = $this->_processPositions($layoutdesign, $newParent, $afterLayoutdesignId);

        $newPath  = sprintf('%s/%s', $newParent->getPath(), $layoutdesign->getId());
        $newLevel = $newParent->getLevel() + 1;
        $levelDisposition = $newLevel - $layoutdesign->getLevel();

        /**
         * Update children nodes path
         */
        $adapter->update(
            $table,
            array(
                'path' => new Zend_Db_Expr(
                    'REPLACE(' . $pathField . ','.
                    $adapter->quote($layoutdesign->getPath() . '/'). ', '.$adapter->quote($newPath . '/').')'
                ),
                'level' => new Zend_Db_Expr($levelFiled . ' + ' . $levelDisposition)
            ),
            array($pathField . ' LIKE ?' => $layoutdesign->getPath() . '/%')
        );
        /**
         * Update moved layout design data
         */
        $data = array(
            'path'  => $newPath,
            'level' => $newLevel,
            'position'  =>$position,
            'parent_id' =>$newParent->getId()
        );
        $adapter->update($table, $data, array('entity_id = ?' => $layoutdesign->getId()));
        // Update layout design object to new data
        $layoutdesign->addData($data);
        return $this;
    }

    /**
     * Process positions of old parent layout design children and new parent layout design children.
     * Get position for moved layout design
     *
     * @access protected
     * @param DLS_DLSBlog_Model_Layoutdesign $layoutdesign
     * @param DLS_DLSBlog_Model_Layoutdesign $newParent
     * @param null|int $afterLayoutdesignId
     * @return int
     * @author Ultimate Module Creator
     */
    protected function _processPositions($layoutdesign, $newParent, $afterLayoutdesignId)
    {
        $table  = $this->getMainTable();
        $adapter= $this->_getWriteAdapter();
        $positionField  = $adapter->quoteIdentifier('position');

        $bind = array(
            'position' => new Zend_Db_Expr($positionField . ' - 1')
        );
        $where = array(
            'parent_id = ?' => $layoutdesign->getParentId(),
            $positionField . ' > ?' => $layoutdesign->getPosition()
        );
        $adapter->update($table, $bind, $where);

        /**
         * Prepare position value
         */
        if ($afterLayoutdesignId) {
            $select = $adapter->select()
                ->from($table, 'position')
                ->where('entity_id = :entity_id');
            $position = $adapter->fetchOne($select, array('entity_id' => $afterLayoutdesignId));
            $bind = array(
                'position' => new Zend_Db_Expr($positionField . ' + 1')
            );
            $where = array(
                'parent_id = ?' => $newParent->getId(),
                $positionField . ' > ?' => $position
            );
            $adapter->update($table, $bind, $where);
        } elseif ($afterLayoutdesignId !== null) {
            $position = 0;
            $bind = array(
                'position' => new Zend_Db_Expr($positionField . ' + 1')
            );
            $where = array(
                'parent_id = ?' => $newParent->getId(),
                $positionField . ' > ?' => $position
            );
            $adapter->update($table, $bind, $where);
        } else {
            $select = $adapter->select()
                ->from($table, array('position' => new Zend_Db_Expr('MIN(' . $positionField. ')')))
                ->where('parent_id = :parent_id');
            $position = $adapter->fetchOne($select, array('parent_id' => $newParent->getId()));
        }
        $position += 1;
        return $position;
    }
}
