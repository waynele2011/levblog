<?php

class DLS_Blog_Model_Filter extends Mage_Core_Model_Abstract {

    const ENTITY = 'dls_blog_filter';
    const CACHE_TAG = 'dls_blog_filter';

    protected $_eventPrefix = 'dls_blog_filter';
    protected $_eventObject = 'filter';
    protected $_taxonomyInstance = null;

    public function _construct() {
        parent::_construct();
        $this->_init('dls_blog/filter');
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

    public function getFilterUrl() {
        if ($this->getUrlKey()) {
            $urlKey = '';
            if ($prefix = Mage::getStoreConfig('dls_blog/filter/url_prefix')) {
                $urlKey .= $prefix . '/';
            }
            $urlKey .= $this->getUrlKey();
            if ($suffix = Mage::getStoreConfig('dls_blog/filter/url_suffix')) {
                $urlKey .= '.' . $suffix;
            }
            return Mage::getUrl('', array('_direct' => $urlKey));
        }
        return Mage::getUrl('dls_blog/filter/view', array('id' => $this->getId()));
    }

    public function checkUrlKey($urlKey, $active = true) {
        return $this->_getResource()->checkUrlKey($urlKey, $active);
    }

    protected function _afterSave() {
        $this->getTaxonomyInstance()->saveFilterRelation($this);
        return parent::_afterSave();
    }

    public function getTaxonomyInstance() {
        if (!$this->_taxonomyInstance) {
            $this->_taxonomyInstance = Mage::getSingleton('dls_blog/filter_taxonomy');
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

    public function getParentBlogset() {
        if (!$this->hasData('_parent_blogset')) {
            if (!$this->getBlogsetId()) {
                return null;
            } else {
                $blogset = Mage::getModel('dls_blog/blogset')
                        ->load($this->getBlogsetId());
                if ($blogset->getId()) {
                    $this->setData('_parent_blogset', $blogset);
                } else {
                    $this->setData('_parent_blogset', null);
                }
            }
        }
        return $this->getData('_parent_blogset');
    }

    public function getParentLayoutdesign() {
        if (!$this->hasData('_parent_layoutdesign')) {
            if (!$this->getLayoutdesignId()) {
                return null;
            } else {
                $layoutdesign = Mage::getModel('dls_blog/layoutdesign')
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
        $values['type'] = '1';

        return $values;
    }

}
