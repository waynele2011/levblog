<?php

class DLS_DLSBlog_Model_Blogset extends Mage_Core_Model_Abstract {

    const ENTITY = 'dls_dlsblog_blogset';
    const CACHE_TAG = 'dls_dlsblog_blogset';

    protected $_eventPrefix = 'dls_dlsblog_blogset';
    protected $_eventObject = 'blogset';
    protected $_taxonomyInstance = null;

    public function _construct() {
        parent::_construct();
        $this->_init('dls_dlsblog/blogset');
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

    public function getBlogsetUrl() {
        if ($this->getUrlKey()) {
            $urlKey = '';
            if ($prefix = Mage::getStoreConfig('dls_dlsblog/blogset/url_prefix')) {
                $urlKey .= $prefix . '/';
            }
            $urlKey .= $this->getUrlKey();
            if ($suffix = Mage::getStoreConfig('dls_dlsblog/blogset/url_suffix')) {
                $urlKey .= '.' . $suffix;
            }
            return Mage::getUrl('', array('_direct' => $urlKey));
        }
        return Mage::getUrl('dls_dlsblog/blogset/view', array('id' => $this->getId()));
    }

    public function checkUrlKey($urlKey, $active = true) {
        return $this->_getResource()->checkUrlKey($urlKey, $active);
    }

    protected function _afterSave() {
        $this->getTaxonomyInstance()->saveBlogsetRelation($this);
        return parent::_afterSave();
    }

    public function getTaxonomyInstance() {
        if (!$this->_taxonomyInstance) {
            $this->_taxonomyInstance = Mage::getSingleton('dls_dlsblog/blogset_taxonomy');
        }
        return $this->_taxonomyInstance;
    }

    public function getSelectedTaxonomies() {
        if (!$this->hasSelectedTaxonomies()) {
            $taxonomies = array();
            foreach ($this->getSelectedTaxonomiesCollection() as $taxonomy) {
                $taxonomies[] = $taxonomy;
            }
            $this->setSelectedTaxonomies($taxonomies);
        }
        return $this->getData('selected_taxonomies');
    }

    public function getSelectedTaxonomiesCollection() {
        $collection = $this->getTaxonomyInstance()->getTaxonomiesCollection($this);
        return $collection;
    }

    public function getSelectedFiltersCollection() {
        if (!$this->hasData('_filter_collection')) {
            if (!$this->getId()) {
                return new Varien_Data_Collection();
            } else {
                $collection = Mage::getResourceModel('dls_dlsblog/filter_collection')
                        ->addFieldToFilter('blogset_id', $this->getId());
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
                        ->addAttributeToFilter('blogset_id', $this->getId());
                $this->setData('_post_collection', $collection);
            }
        }
        return $this->getData('_post_collection');
    }

    public function getParentLayoutdesign() {
        if (!$this->hasData('_parent_layoutdesign')) {
            if (!$this->getLayoutdesignId()) {
                return null;
            } else {
                $layoutdesign = Mage::getModel('dls_dlsblog/layoutdesign')
                        ->load($this->getLayoutdesignId());
                if ($layoutdesign->getId()) {
                    $this->setData('_parent_layoutdesign', $layoutdesign);
                } else {
                    $this->setData('_parent_layoutdesign', null);
                }
            }
        }
        return $this->getData('_parent_layoutdesign');
    }

    public function getDefaultValues() {
        $values = array();
        $values['status'] = 1;
        return $values;
    }

}
