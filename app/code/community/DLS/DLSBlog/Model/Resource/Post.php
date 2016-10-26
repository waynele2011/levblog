<?php

class DLS_DLSBlog_Model_Resource_Post extends Mage_Catalog_Model_Resource_Abstract {

    protected $_postProductTable = null;
    protected $_postFilterTable = null;
    protected $_postTagTable = null;

    public function __construct() {
        $resource = Mage::getSingleton('core/resource');
        $this->setType('dls_dlsblog_post')
                ->setConnection(
                        $resource->getConnection('post_read'), $resource->getConnection('post_write')
        );
        $this->_postProductTable = $this->getTable('dls_dlsblog/post_product');
        $this->_postFilterTable = $this->getTable('dls_dlsblog/post_filter');
        $this->_postTagTable = $this->getTable('dls_dlsblog/post_tag');
    }

    public function getMainTable() {
        return $this->getEntityTable();
    }

    public function checkUrlKey($urlKey, $storeId, $active = true) {
        $stores = array(Mage_Core_Model_App::ADMIN_STORE_ID, $storeId);
        $select = $this->_initCheckUrlKeySelect($urlKey, $stores);
        if (!$select) {
            return false;
        }
        $select->reset(Zend_Db_Select::COLUMNS)
                ->columns('e.entity_id')
                ->limit(1);
        return $this->_getReadAdapter()->fetchOne($select);
    }

    protected function _initCheckUrlKeySelect($urlKey, $store) {
        $urlRewrite = Mage::getModel('eav/config')->getAttribute('dls_dlsblog_post', 'url_key');
        if (!$urlRewrite || !$urlRewrite->getId()) {
            return false;
        }
        $table = $urlRewrite->getBackend()->getTable();
        $select = $this->_getReadAdapter()->select()
                ->from(array('e' => $table))
                ->where('e.attribute_id = ?', $urlRewrite->getId())
                ->where('e.value = ?', $urlKey)
                ->where('e.store_id IN (?)', $store)
                ->order('e.store_id DESC');
        return $select;
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

}
