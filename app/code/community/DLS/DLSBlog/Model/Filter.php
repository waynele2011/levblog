<?php

class DLS_DLSBlog_Model_Filter extends Mage_Core_Model_Abstract {

    const ENTITY = 'dls_dlsblog_filter';
    const CACHE_TAG = 'dls_dlsblog_filter';

    protected $_eventPrefix = 'dls_dlsblog_filter';
    protected $_eventObject = 'filter';
    protected $_postInstance = null;

    public function _construct() {
        parent::_construct();
        $this->_init('dls_dlsblog/filter');
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
            if ($prefix = Mage::getStoreConfig('dls_dlsblog/filter/url_prefix')) {
                $urlKey .= $prefix . '/';
            }
            $urlKey .= $this->getUrlKey();
            if ($suffix = Mage::getStoreConfig('dls_dlsblog/filter/url_suffix')) {
                $urlKey .= '.' . $suffix;
            }
            return Mage::getUrl('', array('_direct' => $urlKey));
        }
        return Mage::getUrl('dls_dlsblog/filter/view', array('id' => $this->getId()));
    }

    public function checkUrlKey($urlKey, $active = true) {
        return $this->_getResource()->checkUrlKey($urlKey, $active);
    }

    protected function _afterSave() {
        $this->getPostInstance()->saveFilterRelation($this);
        return parent::_afterSave();
    }

    public function getPostInstance() {
        if (!$this->_postInstance) {
            $this->_postInstance = Mage::getSingleton('dls_dlsblog/filter_post');
        }
        return $this->_postInstance;
    }

    public function getSelectedPosts() {
        if (!$this->hasSelectedPosts()) {
            $posts = array();
            foreach ($this->getSelectedPostsCollection() as $post) {
                $posts[] = $post;
            }
            $this->setSelectedPosts($posts);
        }
        return $this->getData('selected_posts');
    }

    public function getSelectedPostsCollection() {
        $collection = $this->getPostInstance()->getPostsCollection($this);
        return $collection;
    }

    public function getParentBlogset() {
        if (!$this->hasData('_parent_blogset')) {
            if (!$this->getBlogsetId()) {
                return null;
            } else {
                $blogset = Mage::getModel('dls_dlsblog/blogset')
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

    public function getParentTaxonomy() {
        if (!$this->hasData('_parent_taxonomy')) {
            if (!$this->getTaxonomyId()) {
                return null;
            } else {
                $taxonomy = Mage::getModel('dls_dlsblog/taxonomy')
                        ->load($this->getTaxonomyId());
                if ($taxonomy->getId()) {
                    $this->setData('_parent_taxonomy', $taxonomy);
                } else {
                    $this->setData('_parent_taxonomy', null);
                }
            }
        }
        return $this->getData('_parent_taxonomy');
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
