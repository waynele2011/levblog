<?php

class DLS_Blog_Model_Taxonomy extends Mage_Core_Model_Abstract {

    const ENTITY = 'dls_blog_taxonomy';
    const CACHE_TAG = 'dls_blog_taxonomy';

    protected $_eventPrefix = 'dls_blog_taxonomy';
    protected $_eventObject = 'taxonomy';
    protected $_blogsetInstance = null;
    protected $_filterInstance = null;
    protected $_postInstance = null;

    public function _construct() {
        parent::_construct();
        $this->_init('dls_blog/taxonomy');
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

    public function getTaxonomyUrl() {
        if ($this->getUrlKey()) {
            $urlKey = '';
            if ($prefix = Mage::getStoreConfig('dls_blog/taxonomy/url_prefix')) {
                $urlKey .= $prefix . '/';
            }
            $urlKey .= $this->getUrlKey();
            if ($suffix = Mage::getStoreConfig('dls_blog/taxonomy/url_suffix')) {
                $urlKey .= '.' . $suffix;
            }
            return Mage::getUrl('', array('_direct' => $urlKey));
        }
        return Mage::getUrl('dls_blog/taxonomy/view', array('id' => $this->getId()));
    }

    public function checkUrlKey($urlKey, $active = true) {
        return $this->_getResource()->checkUrlKey($urlKey, $active);
    }

    protected function _afterSave() {
        $this->getBlogsetInstance()->saveTaxonomyRelation($this);
        $this->getFilterInstance()->saveTaxonomyRelation($this);
        $this->getPostInstance()->saveTaxonomyRelation($this);
        return parent::_afterSave();
    }

    public function getBlogsetInstance() {
        if (!$this->_blogsetInstance) {
            $this->_blogsetInstance = Mage::getSingleton('dls_blog/taxonomy_blogset');
        }
        return $this->_blogsetInstance;
    }

    public function getSelectedBlogsets() {
        if (!$this->hasSelectedBlogsets()) {
            $blogsets = array();
            foreach ($this->getSelectedBlogsetsCollection() as $blogset) {
                $blogsets[] = $blogset;
            }
            $this->setSelectedBlogsets($blogsets);
        }
        return $this->getData('selected_blogsets');
    }

    public function getSelectedBlogsetsCollection() {
        $collection = $this->getBlogsetInstance()->getBlogsetsCollection($this);
        return $collection;
    }

    public function getFilterInstance() {
        if (!$this->_filterInstance) {
            $this->_filterInstance = Mage::getSingleton('dls_blog/taxonomy_filter');
        }
        return $this->_filterInstance;
    }

    public function getSelectedFilters() {
        if (!$this->hasSelectedFilters()) {
            $filters = array();
            foreach ($this->getSelectedFiltersCollection() as $filter) {
                $filters[] = $filter;
            }
            $this->setSelectedFilters($filters);
        }
        return $this->getData('selected_filters');
    }

    public function getSelectedFiltersCollection() {
        $collection = $this->getFilterInstance()->getFiltersCollection($this);
        return $collection;
    }

    public function getPostInstance() {
        if (!$this->_postInstance) {
            $this->_postInstance = Mage::getSingleton('dls_blog/taxonomy_post');
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

    public function getTreeModel() {
        return Mage::getResourceModel('dls_blog/taxonomy_tree');
    }

    public function getTreeModelInstance() {
        if (is_null($this->_treeModel)) {
            $this->_treeModel = Mage::getResourceSingleton('dls_blog/taxonomy_tree');
        }
        return $this->_treeModel;
    }

    public function move($parentId, $afterTaxonomyId) {
        $parent = Mage::getModel('dls_blog/taxonomy')->load($parentId);
        if (!$parent->getId()) {
            Mage::throwException(
                    Mage::helper('dls_blog')->__(
                            'Taxonomy move operation is not possible: the new parent taxonomy was not found.'
                    )
            );
        }
        if (!$this->getId()) {
            Mage::throwException(
                    Mage::helper('dls_blog')->__(
                            'Taxonomy move operation is not possible: the current taxonomy was not found.'
                    )
            );
        } elseif ($parent->getId() == $this->getId()) {
            Mage::throwException(
                    Mage::helper('dls_blog')->__(
                            'Taxonomy move operation is not possible: parent taxonomy is equal to child taxonomy.'
                    )
            );
        }
        $this->setMovedTaxonomyId($this->getId());
        $eventParams = array(
            $this->_eventObject => $this,
            'parent' => $parent,
            'taxonomy_id' => $this->getId(),
            'prev_parent_id' => $this->getParentId(),
            'parent_id' => $parentId
        );
        $moveComplete = false;
        $this->_getResource()->beginTransaction();
        try {
            $this->getResource()->changeParent($this, $parent, $afterTaxonomyId);
            $this->_getResource()->commit();
            $this->setAffectedTaxonomyIds(array($this->getId(), $this->getParentId(), $parentId));
            $moveComplete = true;
        } catch (Exception $e) {
            $this->_getResource()->rollBack();
            throw $e;
        }
        if ($moveComplete) {
            Mage::app()->cleanCache(array(self::CACHE_TAG));
        }
        return $this;
    }

    public function getParentTaxonomy() {
        if (!$this->hasData('parent_taxonomy')) {
            $this->setData(
                    'parent_taxonomy', Mage::getModel('dls_blog/taxonomy')->load($this->getParentId())
            );
        }
        return $this->_getData('parent_taxonomy');
    }

    public function getParentId() {
        $parentIds = $this->getParentIds();
        return intval(array_pop($parentIds));
    }

    public function getParentIds() {
        return array_diff($this->getPathIds(), array($this->getId()));
    }

    public function getAllChildren($asArray = false) {
        $children = $this->getResource()->getAllChildren($this);
        if ($asArray) {
            return $children;
        } else {
            return implode(',', $children);
        }
    }

    public function getChildTaxonomies() {
        return implode(',', $this->getResource()->getChildren($this, false));
    }

    public function checkId($id) {
        return $this->_getResource()->checkId($id);
    }

    public function getPathIds() {
        $ids = $this->getData('path_ids');
        if (is_null($ids)) {
            $ids = explode('/', $this->getPath());
            $this->setData('path_ids', $ids);
        }
        return $ids;
    }

    public function getLevel() {
        if (!$this->hasLevel()) {
            return count(explode('/', $this->getPath())) - 1;
        }
        return $this->getData('level');
    }

    public function verifyIds(array $ids) {
        return $this->getResource()->verifyIds($ids);
    }

    public function hasChildren() {
        return $this->_getResource()->getChildrenAmount($this) > 0;
    }

    protected function _beforeDelete() {
        if ($this->getResource()->isForbiddenToDelete($this->getId())) {
            Mage::throwException(Mage::helper('dls_blog')->__("Can't delete root taxonomy."));
        }
        return parent::_beforeDelete();
    }

    public function getTaxonomies($parent, $recursionLevel = 0, $sorted = false, $asCollection = false, $toLoad = true) {
        return $this->getResource()->getTaxonomies($parent, $recursionLevel, $sorted, $asCollection, $toLoad);
    }

    public function getParentTaxonomies() {
        return $this->getResource()->getParentTaxonomies($this);
    }

    public function getChildrenTaxonomies() {
        return $this->getResource()->getChildrenTaxonomies($this);
    }

    public function getStatusPath() {
        $parents = $this->getParentTaxonomies();
        $rootId = Mage::helper('dls_blog/taxonomy')->getRootTaxonomyId();
        foreach ($parents as $parent) {
            if ($parent->getId() == $rootId) {
                continue;
            }
            if (!$parent->getStatus()) {
                return false;
            }
        }
        return $this->getStatus();
    }

    public function getDefaultValues() {
        $values = array();
        $values['status'] = 1;
        return $values;
    }

}
