<?php

class DLS_Blog_Model_Resource_Taxonomy_Tree extends Varien_Data_Tree_Dbp {

    const ID_FIELD = 'entity_id';
    const PATH_FIELD = 'path';
    const ORDER_FIELD = 'order';
    const LEVEL_FIELD = 'level';

    protected $_collection;
    protected $_storeId;
    protected $_inactiveTaxonomyIds = null;

    public function __construct() {
        $resource = Mage::getSingleton('core/resource');
        parent::__construct(
                $resource->getConnection('dls_blog_write'), $resource->getTableName('dls_blog/taxonomy'), array(
            Varien_Data_Tree_Dbp::ID_FIELD => 'entity_id',
            Varien_Data_Tree_Dbp::PATH_FIELD => 'path',
            Varien_Data_Tree_Dbp::ORDER_FIELD => 'position',
            Varien_Data_Tree_Dbp::LEVEL_FIELD => 'level',
                )
        );
    }

    public function getCollection($sorted = false) {
        if (is_null($this->_collection)) {
            $this->_collection = $this->_getDefaultCollection($sorted);
        }
        return $this->_collection;
    }

    public function setCollection($collection) {
        if (!is_null($this->_collection)) {
            destruct($this->_collection);
        }
        $this->_collection = $collection;
        return $this;
    }

    protected function _getDefaultCollection($sorted = false) {
        $collection = Mage::getModel('dls_blog/taxonomy')->getCollection();
        if ($sorted) {
            if (is_string($sorted)) {
                $collection->setOrder($sorted);
            } else {
                $collection->setOrder('name');
            }
        }
        return $collection;
    }

    public function move($taxonomy, $newParent, $prevNode = null) {
        Mage::getResourceSingleton('dls_blog/taxonomy')
                ->move($taxonomy->getId(), $newParent->getId());
        parent::move($taxonomy, $newParent, $prevNode);
        $this->_afterMove($taxonomy, $newParent, $prevNode);
    }

    protected function _afterMove($taxonomy, $newParent, $prevNode) {
        Mage::app()->cleanCache(array(DLS_Blog_Model_Taxonomy::CACHE_TAG));
        return $this;
    }

    public function loadByIds($ids, $addCollectionData = true) {
        $levelField = $this->_conn->quoteIdentifier('level');
        $pathField = $this->_conn->quoteIdentifier('path');
        // load first two levels, if no ids specified
        if (empty($ids)) {
            $select = $this->_conn->select()
                    ->from($this->_table, 'entity_id')
                    ->where($levelField . ' <= 2');
            $ids = $this->_conn->fetchCol($select);
        }
        if (!is_array($ids)) {
            $ids = array($ids);
        }
        foreach ($ids as $key => $id) {
            $ids[$key] = (int) $id;
        }
        // collect paths of specified IDs and prepare to collect all their parents and neighbours
        $select = $this->_conn->select()
                ->from($this->_table, array('path', 'level'))
                ->where('entity_id IN (?)', $ids);
        $where = array($levelField . '=0' => true);

        foreach ($this->_conn->fetchAll($select) as $item) {
            $pathIds = explode('/', $item['path']);
            $level = (int) $item['level'];
            while ($level > 0) {
                $pathIds[count($pathIds) - 1] = '%';
                $path = implode('/', $pathIds);
                $where["$levelField=$level AND $pathField LIKE '$path'"] = true;
                array_pop($pathIds);
                $level--;
            }
        }
        $where = array_keys($where);

        // get all required records
        if ($addCollectionData) {
            $select = $this->_createCollectionDataSelect();
        } else {
            $select = clone $this->_select;
            $select->order($this->_orderField . ' ' . Varien_Db_Select::SQL_ASC);
        }
        $select->where(implode(' OR ', $where));

        // get array of records and add them as nodes to the tree
        $arrNodes = $this->_conn->fetchAll($select);
        if (!$arrNodes) {
            return false;
        }
        $childrenItems = array();
        foreach ($arrNodes as $key => $nodeInfo) {
            $pathToParent = explode('/', $nodeInfo[$this->_pathField]);
            array_pop($pathToParent);
            $pathToParent = implode('/', $pathToParent);
            $childrenItems[$pathToParent][] = $nodeInfo;
        }
        $this->addChildNodes($childrenItems, '', null);
        return $this;
    }

    public function loadBreadcrumbsArray($path, $addCollectionData = true, $withRootNode = false) {
        $pathIds = explode('/', $path);
        if (!$withRootNode) {
            array_shift($pathIds);
        }
        $result = array();
        if (!empty($pathIds)) {
            if ($addCollectionData) {
                $select = $this->_createCollectionDataSelect(false);
            } else {
                $select = clone $this->_select;
            }
            $select
                    ->where('main_table.entity_id IN(?)', $pathIds)
                    ->order($this->_conn->getLengthSql('main_table.path') . ' ' . Varien_Db_Select::SQL_ASC);
            $result = $this->_conn->fetchAll($select);
        }
        return $result;
    }

    protected function _createCollectionDataSelect($sorted = true) {
        $select = $this->_getDefaultCollection($sorted ? $this->_orderField : false)->getSelect();
        $taxonomiesTable = Mage::getSingleton('core/resource')
                ->getTableName('dls_blog/taxonomy');
        $subConcat = $this->_conn->getConcatSql(array('main_table.path', $this->_conn->quote('/%')));
        $subSelect = $this->_conn->select()
                ->from(array('see' => $taxonomiesTable), null)
                ->where('see.entity_id = main_table.entity_id')
                ->orWhere('see.path LIKE ?', $subConcat);
        return $select;
    }

    public function getExistingTaxonomyIdsBySpecifiedIds($ids) {
        if (empty($ids)) {
            return array();
        }
        if (!is_array($ids)) {
            $ids = array($ids);
        }
        $select = $this->_conn->select()
                ->from($this->_table, array('entity_id'))
                ->where('entity_id IN (?)', $ids);
        return $this->_conn->fetchCol($select);
    }

    public function addCollectionData(
    $collection = null, $sorted = false, $exclude = array(), $toLoad = true, $onlyActive = false
    ) {
        if (is_null($collection)) {
            $collection = $this->getCollection($sorted);
        } else {
            $this->setCollection($collection);
        }
        if (!is_array($exclude)) {
            $exclude = array($exclude);
        }
        $nodeIds = array();
        foreach ($this->getNodes() as $node) {
            if (!in_array($node->getId(), $exclude)) {
                $nodeIds[] = $node->getId();
            }
        }
        $collection->addIdFilter($nodeIds);
        if ($onlyActive) {
            $disabledIds = $this->_getDisabledIds($collection);
            if ($disabledIds) {
                $collection->addFieldToFilter('entity_id', array('nin' => $disabledIds));
            }
            $collection->addFieldToFilter('status', 1);
        }
        if ($toLoad) {
            $collection->load();
            foreach ($collection as $taxonomy) {
                if ($this->getNodeById($taxonomy->getId())) {
                    $this->getNodeById($taxonomy->getId())->addData($taxonomy->getData());
                }
            }
            foreach ($this->getNodes() as $node) {
                if (!$collection->getItemById($node->getId()) && $node->getParent()) {
                    $this->removeNode($node);
                }
            }
        }
        return $this;
    }

    public function addInactiveTaxonomyIds($ids) {
        if (!is_array($this->_inactiveTaxonomyIds)) {
            $this->_initInactiveTaxonomyIds();
        }
        $this->_inactiveTaxonomyIds = array_merge($ids, $this->_inactiveTaxonomyIds);
        return $this;
    }

    protected function _initInactiveTaxonomyIds() {
        $this->_inactiveTaxonomyIds = array();
        return $this;
    }

    public function getInactiveTaxonomyIds() {
        if (!is_array($this->_inactiveTaxonomyIds)) {
            $this->_initInactiveTaxonomyIds();
        }
        return $this->_inactiveTaxonomyIds;
    }

    protected function _getDisabledIds($collection) {
        $this->_inactiveItems = $this->getInactiveTaxonomyIds();
        $this->_inactiveItems = array_merge(
                $this->_getInactiveItemIds($collection), $this->_inactiveItems
        );
        $allIds = $collection->getAllIds();
        $disabledIds = array();

        foreach ($allIds as $id) {
            $parents = $this->getNodeById($id)->getPath();
            foreach ($parents as $parent) {
                if (!$this->_getItemIsActive($parent->getId())) {
                    $disabledIds[] = $id;
                    continue;
                }
            }
        }
        return $disabledIds;
    }

    protected function _getInactiveItemIds($collection) {
        $filter = $collection->getAllIdsSql();
        $table = Mage::getSingleton('core/resource')->getTable('dls_blog/taxonomy');
        $bind = array(
            'cond' => 0,
        );
        $select = $this->_conn->select()
                ->from(array('d' => $table), array('d.entity_id'))
                ->where('d.entity_id IN (?)', new Zend_Db_Expr($filter))
                ->where('status = :cond');
        return $this->_conn->fetchCol($select, $bind);
    }

    protected function _getItemIsActive($id) {
        if (!in_array($id, $this->_inactiveItems)) {
            return true;
        }
        return false;
    }

}
