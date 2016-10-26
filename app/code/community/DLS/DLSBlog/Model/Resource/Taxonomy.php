<?php

class DLS_DLSBlog_Model_Resource_Taxonomy extends Mage_Core_Model_Resource_Db_Abstract {

    protected $_tree;

    public function _construct() {
        $this->_init('dls_dlsblog/taxonomy', 'entity_id');
    }

    protected function _getTree() {
        if (!$this->_tree) {
            $this->_tree = Mage::getResourceModel('dls_dlsblog/taxonomy_tree')->load();
        }
        return $this->_tree;
    }

    protected function _beforeDelete(Mage_Core_Model_Abstract $object) {
        parent::_beforeDelete($object);

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

    public function deleteChildren(Varien_Object $object) {
        $adapter = $this->_getWriteAdapter();
        $pathField = $adapter->quoteIdentifier('path');
        $select = $adapter->select()
                ->from($this->getMainTable(), array('entity_id'))
                ->where($pathField . ' LIKE :c_path');
        $childrenIds = $adapter->fetchCol($select, array('c_path' => $object->getPath() . '/%'));
        if (!empty($childrenIds)) {
            $adapter->delete(
                    $this->getMainTable(), array('entity_id IN (?)' => $childrenIds)
            );
        }

        $object->setDeletedChildrenIds($childrenIds);
        return $this;
    }

    protected function _afterSave(Mage_Core_Model_Abstract $object) {
        if (substr($object->getPath(), -1) == '/') {
            $object->setPath($object->getPath() . $object->getId());
            $this->_savePath($object);
        }


        return parent::_afterSave($object);
    }

    protected function _savePath($object) {
        if ($object->getId()) {
            $this->_getWriteAdapter()->update(
                    $this->getMainTable(), array('path' => $object->getPath()), array('entity_id = ?' => $object->getId())
            );
        }
        return $this;
    }

    protected function _getMaxPosition($path) {
        $adapter = $this->getReadConnection();
        $positionField = $adapter->quoteIdentifier('position');
        $level = count(explode('/', $path));
        $bind = array(
            'c_level' => $level,
            'c_path' => $path . '/%'
        );
        $select = $adapter->select()
                ->from($this->getMainTable(), 'MAX(' . $positionField . ')')
                ->where($adapter->quoteIdentifier('path') . ' LIKE :c_path')
                ->where($adapter->quoteIdentifier('level') . ' = :c_level');

        $position = $adapter->fetchOne($select, $bind);
        if (!$position) {
            $position = 0;
        }
        return $position;
    }

    public function getChildrenCount($taxonomyId) {
        $select = $this->_getReadAdapter()->select()
                ->from($this->getMainTable(), 'children_count')
                ->where('entity_id = :entity_id');
        $bind = array('entity_id' => $taxonomyId);
        return $this->_getReadAdapter()->fetchOne($select, $bind);
    }

    public function checkId($entityId) {
        $select = $this->_getReadAdapter()->select()
                ->from($this->getMainTable(), 'entity_id')
                ->where('entity_id = :entity_id');
        $bind = array('entity_id' => $entityId);
        return $this->_getReadAdapter()->fetchOne($select, $bind);
    }

    public function verifyIds(array $ids) {
        if (empty($ids)) {
            return array();
        }
        $select = $this->_getReadAdapter()->select()
                ->from($this->getMainTable(), 'entity_id')
                ->where('entity_id IN(?)', $ids);

        return $this->_getReadAdapter()->fetchCol($select);
    }

    public function getChildrenAmount($taxonomy, $isActiveFlag = true) {
        $bind = array(
            'active_flag' => $isActiveFlag,
            'c_path' => $taxonomy->getPath() . '/%'
        );
        $select = $this->_getReadAdapter()->select()
                ->from(array('m' => $this->getMainTable()), array('COUNT(m.entity_id)'))
                ->where('m.path LIKE :c_path')
                ->where('status' . ' = :active_flag');
        return $this->_getReadAdapter()->fetchOne($select, $bind);
    }

    public function getParentTaxonomies($taxonomy) {
        $pathIds = array_reverse(explode('/', $taxonomy->getPath()));
        $taxonomies = Mage::getResourceModel('dls_dlsblog/taxonomy_collection')
                ->addFieldToFilter('entity_id', array('in' => $pathIds))
                ->load()
                ->getItems();
        return $taxonomies;
    }

    public function getChildrenTaxonomies($taxonomy) {
        $collection = $taxonomy->getCollection();
        $collection
                ->addIdFilter($taxonomy->getChildTaxonomies())
                ->setOrder('position', Varien_Db_Select::SQL_ASC)
                ->load();
        return $collection;
    }

    public function getChildren($taxonomy, $recursive = true) {
        $bind = array(
            'c_path' => $taxonomy->getPath() . '/%'
        );
        $select = $this->_getReadAdapter()->select()
                ->from(array('m' => $this->getMainTable()), 'entity_id')
                ->where('status = ?', 1)
                ->where($this->_getReadAdapter()->quoteIdentifier('path') . ' LIKE :c_path');
        if (!$recursive) {
            $select->where($this->_getReadAdapter()->quoteIdentifier('level') . ' <= :c_level');
            $bind['c_level'] = $taxonomy->getLevel() + 1;
        }
        return $this->_getReadAdapter()->fetchCol($select, $bind);
    }

    protected function _beforeSave(Mage_Core_Model_Abstract $object) {
        if (!$object->getInitialSetupFlag()) {
            $urlKey = $object->getData('url_key');
            if ($urlKey == '') {
                $urlKey = $object->getName();
            }
            $urlKey = $this->formatUrlKey($urlKey);
            $validKey = false;
            while (!$validKey) {
                $entityId = $this->checkUrlKey($urlKey, $object->getStoreId(), false);
                if ($entityId == $object->getId() || empty($entityId)) {
                    $validKey = true;
                } else {
                    $parts = explode('-', $urlKey);
                    $last = $parts[count($parts) - 1];
                    if (!is_numeric($last)) {
                        $urlKey = $urlKey . '-1';
                    } else {
                        $suffix = '-' . ($last + 1);
                        unset($parts[count($parts) - 1]);
                        $urlKey = implode('-', $parts) . $suffix;
                    }
                }
            }
            $object->setData('url_key', $urlKey);
        }
        parent::_beforeSave($object);
        if (!$object->getChildrenCount()) {
            $object->setChildrenCount(0);
        }
        if ($object->getLevel() === null) {
            $object->setLevel(1);
        }
        if (!$object->getId() && !$object->getInitialSetupFlag()) {
            $object->setPosition($this->_getMaxPosition($object->getPath()) + 1);
            $path = explode('/', $object->getPath());
            $level = count($path);
            $object->setLevel($level);
            if ($level) {
                $object->setParentId($path[$level - 1]);
            }
            $object->setPath($object->getPath() . '/');
            $toUpdateChild = explode('/', $object->getPath());
            $this->_getWriteAdapter()->update(
                    $this->getMainTable(), array('children_count' => new Zend_Db_Expr('children_count+1')), array('entity_id IN(?)' => $toUpdateChild)
            );
        }
        return $this;
    }

    public function getTaxonomies(
    $parent, $recursionLevel = 0, $sorted = false, $asCollection = false, $toLoad = true
    ) {
        $tree = Mage::getResourceModel('dls_dlsblog/taxonomy_tree');
        $nodes = $tree->loadNode($parent)
                ->loadChildren($recursionLevel)
                ->getChildren();
        $tree->addCollectionData(null, $sorted, $parent, $toLoad, true);
        if ($asCollection) {
            return $tree->getCollection();
        }
        return $nodes;
    }

    public function getAllChildren($taxonomy) {
        $children = $this->getChildren($taxonomy);
        $myId = array($taxonomy->getId());
        $children = array_merge($myId, $children);
        return $children;
    }

    public function isForbiddenToDelete($taxonomyId) {
        return ($taxonomyId == Mage::helper('dls_dlsblog/taxonomy')->getRootTaxonomyId());
    }

    public function getTaxonomyPathById($taxonomyId) {
        $select = $this->getReadConnection()->select()
                ->from($this->getMainTable(), array('path'))
                ->where('entity_id = :entity_id');
        $bind = array('entity_id' => (int) $taxonomyId);
        return $this->getReadConnection()->fetchOne($select, $bind);
    }

    public function changeParent(
    DLS_DLSBlog_Model_Taxonomy $taxonomy, DLS_DLSBlog_Model_Taxonomy $newParent, $afterTaxonomyId = null
    ) {
        $childrenCount = $this->getChildrenCount($taxonomy->getId()) + 1;
        $table = $this->getMainTable();
        $adapter = $this->_getWriteAdapter();
        $levelFiled = $adapter->quoteIdentifier('level');
        $pathField = $adapter->quoteIdentifier('path');


        $adapter->update(
                $table, array('children_count' => new Zend_Db_Expr('children_count - ' . $childrenCount)), array('entity_id IN(?)' => $taxonomy->getParentIds())
        );

        $adapter->update(
                $table, array('children_count' => new Zend_Db_Expr('children_count + ' . $childrenCount)), array('entity_id IN(?)' => $newParent->getPathIds())
        );

        $position = $this->_processPositions($taxonomy, $newParent, $afterTaxonomyId);

        $newPath = sprintf('%s/%s', $newParent->getPath(), $taxonomy->getId());
        $newLevel = $newParent->getLevel() + 1;
        $levelDisposition = $newLevel - $taxonomy->getLevel();


        $adapter->update(
                $table, array(
            'path' => new Zend_Db_Expr(
                    'REPLACE(' . $pathField . ',' .
                    $adapter->quote($taxonomy->getPath() . '/') . ', ' . $adapter->quote($newPath . '/') . ')'
            ),
            'level' => new Zend_Db_Expr($levelFiled . ' + ' . $levelDisposition)
                ), array($pathField . ' LIKE ?' => $taxonomy->getPath() . '/%')
        );

        $data = array(
            'path' => $newPath,
            'level' => $newLevel,
            'position' => $position,
            'parent_id' => $newParent->getId()
        );
        $adapter->update($table, $data, array('entity_id = ?' => $taxonomy->getId()));
        // Update taxonomy object to new data
        $taxonomy->addData($data);
        return $this;
    }

    protected function _processPositions($taxonomy, $newParent, $afterTaxonomyId) {
        $table = $this->getMainTable();
        $adapter = $this->_getWriteAdapter();
        $positionField = $adapter->quoteIdentifier('position');

        $bind = array(
            'position' => new Zend_Db_Expr($positionField . ' - 1')
        );
        $where = array(
            'parent_id = ?' => $taxonomy->getParentId(),
            $positionField . ' > ?' => $taxonomy->getPosition()
        );
        $adapter->update($table, $bind, $where);


        if ($afterTaxonomyId) {
            $select = $adapter->select()
                    ->from($table, 'position')
                    ->where('entity_id = :entity_id');
            $position = $adapter->fetchOne($select, array('entity_id' => $afterTaxonomyId));
            $bind = array(
                'position' => new Zend_Db_Expr($positionField . ' + 1')
            );
            $where = array(
                'parent_id = ?' => $newParent->getId(),
                $positionField . ' > ?' => $position
            );
            $adapter->update($table, $bind, $where);
        } elseif ($afterTaxonomyId !== null) {
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
                    ->from($table, array('position' => new Zend_Db_Expr('MIN(' . $positionField . ')')))
                    ->where('parent_id = :parent_id');
            $position = $adapter->fetchOne($select, array('parent_id' => $newParent->getId()));
        }
        $position += 1;
        return $position;
    }

    public function checkUrlKey($urlKey, $storeId, $active = true) {
        $stores = array(Mage_Core_Model_App::ADMIN_STORE_ID, $storeId);
        $select = $this->_initCheckUrlKeySelect($urlKey, $stores);
        if ($active) {
            $select->where('e.status = ?', $active);
        }
        $select->reset(Zend_Db_Select::COLUMNS)
                ->columns('e.entity_id')
                ->limit(1);

        return $this->_getReadAdapter()->fetchOne($select);
    }

    public function getIsUniqueUrlKey(Mage_Core_Model_Abstract $object) {
        if (Mage::app()->isSingleStoreMode() || !$object->hasStores()) {
            $stores = array(Mage_Core_Model_App::ADMIN_STORE_ID);
        } else {
            $stores = (array) $object->getData('stores');
        }
        $select = $this->_initCheckUrlKeySelect($object->getData('url_key'), $stores);
        if ($object->getId()) {
            $select->where('e.entity_id <> ?', $object->getId());
        }
        if ($this->_getWriteAdapter()->fetchRow($select)) {
            return false;
        }
        return true;
    }

    protected function isNumericUrlKey(Mage_Core_Model_Abstract $object) {
        return preg_match('/^[0-9]+$/', $object->getData('url_key'));
    }

    protected function isValidUrlKey(Mage_Core_Model_Abstract $object) {
        return preg_match('/^[a-z0-9][a-z0-9_\/-]+(\.[a-z0-9_-]+)?$/', $object->getData('url_key'));
    }

    public function formatUrlKey($str) {
        $urlKey = preg_replace('#[^0-9a-z]+#i', '-', Mage::helper('catalog/product_url')->format($str));
        $urlKey = strtolower($urlKey);
        $urlKey = trim($urlKey, '-');
        return $urlKey;
    }

    protected function _initCheckUrlKeySelect($urlKey, $store) {
        $select = $this->_getReadAdapter()->select()
                ->from(array('e' => $this->getMainTable()))
                ->where('e.url_key = ?', $urlKey);
        return $select;
    }

}
