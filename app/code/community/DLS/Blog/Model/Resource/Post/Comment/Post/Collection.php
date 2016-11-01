<?php

class DLS_Blog_Model_Resource_Post_Comment_Post_Collection extends DLS_Blog_Model_Resource_Post_Collection {

    protected $_entitiesAlias = array();
    protected $_commentStoreTable;
    protected $_addStoreDataFlag = false;
    protected $_storesIds = array();

    protected function _construct() {
        $this->_init('dls_blog/post');
        $this->_setIdFieldName('comment_id');
        $this->_commentStoreTable = Mage::getSingleton('core/resource')
                ->getTableName('dls_blog/post_comment_store');
    }

    protected function _initSelect() {
        parent::_initSelect();
        $this->_joinFields();
        return $this;
    }

    public function addCustomerFilter($customerId) {
        $this->getSelect()->where('ct.customer_id = ?', $customerId);
        return $this;
    }

    public function addEntityFilter($entityId) {
        $this->getSelect()->where('ct.post_id = ?', $entityId);
        return $this;
    }

    public function addStatusFilter($status = 1) {
        $this->getSelect()->where('ct.status = ?', $status);
        return $this;
    }

    public function setDateOrder($dir = 'DESC') {
        $this->setOrder('ct.created_at', $dir);
        return $this;
    }

    protected function _joinFields() {
        $commentTable = Mage::getSingleton('core/resource')
                ->getTableName('dls_blog/post_comment');
        $this->addAttributeToSelect('title');
        $this->getSelect()->join(
                array('ct' => $commentTable), 'ct.post_id = e.entity_id', array(
            'ct_title' => 'title',
            'ct_comment_id' => 'comment_id',
            'ct_name' => 'name',
            'ct_status' => 'status',
            'ct_email' => 'email',
            'ct_created_at' => 'created_at',
            'ct_updated_at' => 'updated_at'
                )
        );
        return $this;
    }

    public function getAllIds($limit = null, $offset = null) {
        $idsSelect = clone $this->getSelect();
        $idsSelect->reset(Zend_Db_Select::ORDER);
        $idsSelect->reset(Zend_Db_Select::LIMIT_COUNT);
        $idsSelect->reset(Zend_Db_Select::LIMIT_OFFSET);
        $idsSelect->reset(Zend_Db_Select::COLUMNS);
        $idsSelect->columns('ct.comment_id');
        return $this->getConnection()->fetchCol($idsSelect);
    }

    public function getColumnValues($colName) {
        $col = array();
        foreach ($this->getItems() as $item) {
            $col[] = $item->getData($colName);
        }
        return $col;
    }

    public function getSelectCountSql() {
        $select = parent::getSelectCountSql();
        $this->_applyStoresFilterToSelect($select);
        $select->reset(Zend_Db_Select::COLUMNS)
                ->columns('COUNT(e.entity_id)')
                ->reset(Zend_Db_Select::HAVING);

        return $select;
    }

    public function addStoreFilter($storeId = null, $withAdmin = true) {
        if (is_null($storeId)) {
            $storeId = $this->getStoreId();
        }
        if (!is_array($storeId)) {
            $storeId = array($storeId);
        }
        if (!empty($this->_storesIds)) {
            $this->_storesIds = array_intersect($this->_storesIds, $storeId);
        } else {
            $this->_storesIds = $storeId;
        }

        return $this;
    }

    public function setStoreFilter($storeId, $withAdmin = false) {
        if (is_array($storeId) && isset($storeId['eq'])) {
            $storeId = array_shift($storeId);
        }

        if (!is_array($storeId)) {
            $storeId = array($storeId);
        }

        if (!empty($this->_storesIds)) {
            $this->_storesIds = array_intersect($this->_storesIds, $storeId);
        } else {
            $this->_storesIds = $storeId;
        }
        if ($withAdmin) {
            $this->_storesIds = array_merge($this->_storesIds, array(0));
        }
        return $this;
    }

    protected function _applyStoresFilterToSelect(Zend_Db_Select $select = null) {
        $adapter = $this->getConnection();
        $storesIds = $this->_storesIds;
        if (is_null($select)) {
            $select = $this->getSelect();
        }

        if (is_array($storesIds) && (count($storesIds) == 1)) {
            $storesIds = array_shift($storesIds);
        }

        if (is_array($storesIds) && !empty($storesIds)) {
            $inCond = $adapter->prepareSqlCondition('store.store_id', array('in' => $storesIds));
            $select->join(
                            array('store' => $this->_commentStoreTable), 'ct.comment_id=store.comment_id AND ' . $inCond, array()
                    )
                    ->group('ct.comment_id');

            $this->_useAnalyticFunction = true;
        } elseif (!empty($storesIds)) {
            $select->join(
                    array('store' => $this->_commentStoreTable), $adapter->quoteInto('ct.comment_id=store.comment_id AND store.store_id = ?', (int) $storesIds), array()
            );
        }
        return $this;
    }

    public function addStoreData() {
        $this->_addStoreDataFlag = true;
        return $this;
    }

    protected function _afterLoad() {
        parent::_afterLoad();
        if ($this->_addStoreDataFlag) {
            $this->_addStoreData();
        }
        return $this;
    }

    protected function _addStoreData() {
        $adapter = $this->getConnection();
        $commentIds = $this->getColumnValues('ct_comment_id');
        $storesToComments = array();
        if (count($commentIds) > 0) {
            $commentIdCondition = $this->_getConditionSql('comment_id', array('in' => $commentIds));
            $select = $adapter->select()
                    ->from($this->_commentStoreTable)
                    ->where($commentIdCondition);
            $result = $adapter->fetchAll($select);
            foreach ($result as $row) {
                if (!isset($storesToComments[$row['comment_id']])) {
                    $storesToComments[$row['comment_id']] = array();
                }
                $storesToComments[$row['comment_id']][] = $row['store_id'];
            }
        }

        foreach ($this as $item) {
            if (isset($storesToComments[$item->getCtCommentId()])) {
                $item->setData('stores', $storesToComments[$item->getCtCommentId()]);
            } else {
                $item->setData('stores', array());
            }
        }
        return $this;
    }

    public function addAttributeToFilter($attribute, $condition = null, $joinType = 'inner') {
        switch ($attribute) {
            case 'ct.comment_id':
            case 'ct.created_at':
            case 'ct.status':
            case 'ct.title':
            case 'ct.name':
            case 'ct.email':
            case 'ct.comment':
            case 'ct.updated_at':
                $conditionSql = $this->_getConditionSql($attribute, $condition);
                $this->getSelect()->where($conditionSql);
                break;

            case 'stores':
                $this->setStoreFilter($condition);
                break;
            default:
                parent::addAttributeToFilter($attribute, $condition, $joinType);
                break;
        }
        return $this;
    }

}
