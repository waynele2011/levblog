<?php

class DLS_DLSBlog_Model_Resource_Eav_Attribute extends Mage_Eav_Model_Entity_Attribute {

    const MODULE_NAME = 'DLS_DLSBlog';
    const ENTITY = 'dls_dlsblog_eav_attribute';

    protected $_eventPrefix = 'dls_dlsblog_entity_attribute';
    protected $_eventObject = 'attribute';
    static protected $_labels = null;

    protected function _construct() {
        $this->_init('dls_dlsblog/attribute');
    }

    public function isScopeStore() {
        return $this->getIsGlobal() == Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE;
    }

    public function isScopeWebsite() {
        return $this->getIsGlobal() == Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE;
    }

    public function isScopeGlobal() {
        return (!$this->isScopeStore() && !$this->isScopeWebsite());
    }

    public function getBackendTypeByInput($type) {
        switch ($type) {
            case 'file':
            //intentional fallthrough
            case 'image':
                return 'varchar';
                break;
            default:
                return parent::getBackendTypeByInput($type);
                break;
        }
    }

    protected function _beforeDelete() {
        if (!$this->getIsUserDefined()) {
            throw new Mage_Core_Exception(
            Mage::helper('dls_dlsblog')->__('This attribute is not deletable')
            );
        }
        return parent::_beforeDelete();
    }

}
