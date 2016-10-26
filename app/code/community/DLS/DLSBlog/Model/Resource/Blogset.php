<?php

class DLS_DLSBlog_Model_Resource_Blogset extends Mage_Core_Model_Resource_Db_Abstract {

    public function _construct() {
        $this->_init('dls_dlsblog/blogset', 'entity_id');
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

    protected function _beforeSave(Mage_Core_Model_Abstract $object) {
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
        return parent::_beforeSave($object);
    }

}
