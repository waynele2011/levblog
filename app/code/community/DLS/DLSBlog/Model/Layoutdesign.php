<?php

class DLS_DLSBlog_Model_Layoutdesign extends Mage_Core_Model_Abstract {

    const ENTITY = 'dls_dlsblog_layoutdesign';
    const CACHE_TAG = 'dls_dlsblog_layoutdesign';

    protected $_eventPrefix = 'dls_dlsblog_layoutdesign';
    protected $_eventObject = 'layoutdesign';

    public function _construct() {
        parent::_construct();
        $this->_init('dls_dlsblog/layoutdesign');
    }

    protected function _beforeSave() {
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }

    protected function _afterSave() {
        return parent::_afterSave();
    }

    public function getSelectedBlogsetsCollection() {
        if (!$this->hasData('_blogset_collection')) {
            if (!$this->getId()) {
                return new Varien_Data_Collection();
            } else {
                $collection = Mage::getResourceModel('dls_dlsblog/blogset_collection')
                        ->addFieldToFilter('layoutdesign_id', $this->getId());
                $this->setData('_blogset_collection', $collection);
            }
        }
        return $this->getData('_blogset_collection');
    }

    public function getSelectedFiltersCollection() {
        if (!$this->hasData('_filter_collection')) {
            if (!$this->getId()) {
                return new Varien_Data_Collection();
            } else {
                $collection = Mage::getResourceModel('dls_dlsblog/filter_collection')
                        ->addFieldToFilter('layoutdesign_id', $this->getId());
                $this->setData('_filter_collection', $collection);
            }
        }
        return $this->getData('_filter_collection');
    }

    public function getSelectedPostsCollection() {
        if (!$this->hasData('_post_collection')) {
            if (!$this->getId()) {
                return new Varien_Data_Collection();
            } else {
                $collection = Mage::getResourceModel('dls_dlsblog/post_collection')->addAttributeToSelect('*')
                        ->addAttributeToFilter('layoutdesign_id', $this->getId());
                $this->setData('_post_collection', $collection);
            }
        }
        return $this->getData('_post_collection');
    }

    public function getDefaultValues() {
        $values = array();
        $values['status'] = 1;
        return $values;
    }

}
