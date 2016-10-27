<?php

/**
 * Taxonomy model
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Model_Taxonomy extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'dls_dlsblog_taxonomy';
    const CACHE_TAG = 'dls_dlsblog_taxonomy';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'dls_dlsblog_taxonomy';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'taxonomy';
    protected $_blogsetInstance = null;
    protected $_filterInstance = null;
    protected $_postInstance = null;

    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('dls_dlsblog/taxonomy');
    }

    /**
     * before save taxonomy
     *
     * @access protected
     * @return DLS_DLSBlog_Model_Taxonomy
     * @author Ultimate Module Creator
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }

    /**
     * get the url to the taxonomy details page
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getTaxonomyUrl()
    {
        if ($this->getUrlKey()) {
            $urlKey = '';
            if ($prefix = Mage::getStoreConfig('dls_dlsblog/taxonomy/url_prefix')) {
                $urlKey .= $prefix.'/';
            }
            $urlKey .= $this->getUrlKey();
            if ($suffix = Mage::getStoreConfig('dls_dlsblog/taxonomy/url_suffix')) {
                $urlKey .= '.'.$suffix;
            }
            return Mage::getUrl('', array('_direct'=>$urlKey));
        }
        return Mage::getUrl('dls_dlsblog/taxonomy/view', array('id'=>$this->getId()));
    }

    /**
     * check URL key
     *
     * @access public
     * @param string $urlKey
     * @param bool $active
     * @return mixed
     * @author Ultimate Module Creator
     */
    public function checkUrlKey($urlKey, $active = true)
    {
        return $this->_getResource()->checkUrlKey($urlKey, $active);
    }

    /**
     * save taxonomy relation
     *
     * @access public
     * @return DLS_DLSBlog_Model_Taxonomy
     * @author Ultimate Module Creator
     */
    protected function _afterSave()
    {
        $this->getBlogsetInstance()->saveTaxonomyRelation($this);
        $this->getFilterInstance()->saveTaxonomyRelation($this);
        $this->getPostInstance()->saveTaxonomyRelation($this);
        return parent::_afterSave();
    }

    /**
     * get blog setting relation model
     *
     * @access public
     * @return DLS_DLSBlog_Model_Taxonomy_Blogset
     * @author Ultimate Module Creator
     */
    public function getBlogsetInstance()
    {
        if (!$this->_blogsetInstance) {
            $this->_blogsetInstance = Mage::getSingleton('dls_dlsblog/taxonomy_blogset');
        }
        return $this->_blogsetInstance;
    }

    /**
     * get selected  array
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getSelectedBlogsets()
    {
        if (!$this->hasSelectedBlogsets()) {
            $blogsets = array();
            foreach ($this->getSelectedBlogsetsCollection() as $blogset) {
                $blogsets[] = $blogset;
            }
            $this->setSelectedBlogsets($blogsets);
        }
        return $this->getData('selected_blogsets');
    }

    /**
     * Retrieve collection selected 
     *
     * @access public
     * @return DLS_DLSBlog_Model_Taxonomy_Blogset_Collection
     * @author Ultimate Module Creator
     */
    public function getSelectedBlogsetsCollection()
    {
        $collection = $this->getBlogsetInstance()->getBlogsetsCollection($this);
        return $collection;
    }

    /**
     * get filter relation model
     *
     * @access public
     * @return DLS_DLSBlog_Model_Taxonomy_Filter
     * @author Ultimate Module Creator
     */
    public function getFilterInstance()
    {
        if (!$this->_filterInstance) {
            $this->_filterInstance = Mage::getSingleton('dls_dlsblog/taxonomy_filter');
        }
        return $this->_filterInstance;
    }

    /**
     * get selected  array
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getSelectedFilters()
    {
        if (!$this->hasSelectedFilters()) {
            $filters = array();
            foreach ($this->getSelectedFiltersCollection() as $filter) {
                $filters[] = $filter;
            }
            $this->setSelectedFilters($filters);
        }
        return $this->getData('selected_filters');
    }

    /**
     * Retrieve collection selected 
     *
     * @access public
     * @return DLS_DLSBlog_Model_Taxonomy_Filter_Collection
     * @author Ultimate Module Creator
     */
    public function getSelectedFiltersCollection()
    {
        $collection = $this->getFilterInstance()->getFiltersCollection($this);
        return $collection;
    }

    /**
     * get post relation model
     *
     * @access public
     * @return DLS_DLSBlog_Model_Taxonomy_Post
     * @author Ultimate Module Creator
     */
    public function getPostInstance()
    {
        if (!$this->_postInstance) {
            $this->_postInstance = Mage::getSingleton('dls_dlsblog/taxonomy_post');
        }
        return $this->_postInstance;
    }

    /**
     * get selected  array
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getSelectedPosts()
    {
        if (!$this->hasSelectedPosts()) {
            $posts = array();
            foreach ($this->getSelectedPostsCollection() as $post) {
                $posts[] = $post;
            }
            $this->setSelectedPosts($posts);
        }
        return $this->getData('selected_posts');
    }

    /**
     * Retrieve collection selected 
     *
     * @access public
     * @return DLS_DLSBlog_Model_Taxonomy_Post_Collection
     * @author Ultimate Module Creator
     */
    public function getSelectedPostsCollection()
    {
        $collection = $this->getPostInstance()->getPostsCollection($this);
        return $collection;
    }

    /**
     * Retrieve parent 
     *
     * @access public
     * @return null|DLS_DLSBlog_Model_Layoutdesign
     * @author Ultimate Module Creator
     */
    public function getParentLayoutdesign()
    {
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

    /**
     * get the tree model
     *
     * @access public
     * @return DLS_DLSBlog_Model_Resource_Taxonomy_Tree
     * @author Ultimate Module Creator
     */
    public function getTreeModel()
    {
        return Mage::getResourceModel('dls_dlsblog/taxonomy_tree');
    }

    /**
     * get tree model instance
     *
     * @access public
     * @return DLS_DLSBlog_Model_Resource_Taxonomy_Tree
     * @author Ultimate Module Creator
     */
    public function getTreeModelInstance()
    {
        if (is_null($this->_treeModel)) {
            $this->_treeModel = Mage::getResourceSingleton('dls_dlsblog/taxonomy_tree');
        }
        return $this->_treeModel;
    }

    /**
     * Move taxonomy
     *
     * @access public
     * @param   int $parentId new parent taxonomy id
     * @param   int $afterTaxonomyId taxonomy id after which we have put current taxonomy
     * @return  DLS_DLSBlog_Model_Taxonomy
     * @author Ultimate Module Creator
     */
    public function move($parentId, $afterTaxonomyId)
    {
        $parent = Mage::getModel('dls_dlsblog/taxonomy')->load($parentId);
        if (!$parent->getId()) {
            Mage::throwException(
                Mage::helper('dls_dlsblog')->__(
                    'Taxonomy move operation is not possible: the new parent taxonomy was not found.'
                )
            );
        }
        if (!$this->getId()) {
            Mage::throwException(
                Mage::helper('dls_dlsblog')->__(
                    'Taxonomy move operation is not possible: the current taxonomy was not found.'
                )
            );
        } elseif ($parent->getId() == $this->getId()) {
            Mage::throwException(
                Mage::helper('dls_dlsblog')->__(
                    'Taxonomy move operation is not possible: parent taxonomy is equal to child taxonomy.'
                )
            );
        }
        $this->setMovedTaxonomyId($this->getId());
        $eventParams = array(
            $this->_eventObject => $this,
            'parent'            => $parent,
            'taxonomy_id'     => $this->getId(),
            'prev_parent_id'    => $this->getParentId(),
            'parent_id'         => $parentId
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

    /**
     * Get the parent taxonomy
     *
     * @access public
     * @return  DLS_DLSBlog_Model_Taxonomy
     * @author Ultimate Module Creator
     */
    public function getParentTaxonomy()
    {
        if (!$this->hasData('parent_taxonomy')) {
            $this->setData(
                'parent_taxonomy',
                Mage::getModel('dls_dlsblog/taxonomy')->load($this->getParentId())
            );
        }
        return $this->_getData('parent_taxonomy');
    }

    /**
     * Get the parent id
     *
     * @access public
     * @return  int
     * @author Ultimate Module Creator
     */
    public function getParentId()
    {
        $parentIds = $this->getParentIds();
        return intval(array_pop($parentIds));
    }

    /**
     * Get all parent taxonomies ids
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getParentIds()
    {
        return array_diff($this->getPathIds(), array($this->getId()));
    }

    /**
     * Get all taxonomies children
     *
     * @access public
     * @param bool $asArray
     * @return mixed (array|string)
     * @author Ultimate Module Creator
     */
    public function getAllChildren($asArray = false)
    {
        $children = $this->getResource()->getAllChildren($this);
        if ($asArray) {
            return $children;
        } else {
            return implode(',', $children);
        }
    }

    /**
     * Get all taxonomies children
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getChildTaxonomies()
    {
        return implode(',', $this->getResource()->getChildren($this, false));
    }

    /**
     * check the id
     *
     * @access public
     * @param int $id
     * @return bool
     * @author Ultimate Module Creator
     */
    public function checkId($id)
    {
        return $this->_getResource()->checkId($id);
    }

    /**
     * Get array taxonomies ids which are part of taxonomy path
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getPathIds()
    {
        $ids = $this->getData('path_ids');
        if (is_null($ids)) {
            $ids = explode('/', $this->getPath());
            $this->setData('path_ids', $ids);
        }
        return $ids;
    }

    /**
     * Retrieve level
     *
     * @access public
     * @return int
     * @author Ultimate Module Creator
     */
    public function getLevel()
    {
        if (!$this->hasLevel()) {
            return count(explode('/', $this->getPath())) - 1;
        }
        return $this->getData('level');
    }

    /**
     * Verify taxonomy ids
     *
     * @access public
     * @param array $ids
     * @return bool
     * @author Ultimate Module Creator
     */
    public function verifyIds(array $ids)
    {
        return $this->getResource()->verifyIds($ids);
    }

    /**
     * check if taxonomy has children
     *
     * @access public
     * @return bool
     * @author Ultimate Module Creator
     */
    public function hasChildren()
    {
        return $this->_getResource()->getChildrenAmount($this) > 0;
    }

    /**
     * check if taxonomy can be deleted
     *
     * @access protected
     * @return DLS_DLSBlog_Model_Taxonomy
     * @author Ultimate Module Creator
     */
    protected function _beforeDelete()
    {
        if ($this->getResource()->isForbiddenToDelete($this->getId())) {
            Mage::throwException(Mage::helper('dls_dlsblog')->__("Can't delete root taxonomy."));
        }
        return parent::_beforeDelete();
    }

    /**
     * get the taxonomies
     *
     * @access public
     * @param DLS_DLSBlog_Model_Taxonomy $parent
     * @param int $recursionLevel
     * @param bool $sorted
     * @param bool $asCollection
     * @param bool $toLoad
     * @author Ultimate Module Creator
     */
    public function getTaxonomies($parent, $recursionLevel = 0, $sorted=false, $asCollection=false, $toLoad=true)
    {
        return $this->getResource()->getTaxonomies($parent, $recursionLevel, $sorted, $asCollection, $toLoad);
    }

    /**
     * Return parent taxonomies of current taxonomy
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getParentTaxonomies()
    {
        return $this->getResource()->getParentTaxonomies($this);
    }

    /**
     * Return children taxonomies of current taxonomy
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getChildrenTaxonomies()
    {
        return $this->getResource()->getChildrenTaxonomies($this);
    }

    /**
     * check if parents are enabled
     *
     * @access public
     * @return bool
     * @author Ultimate Module Creator
     */
    public function getStatusPath()
    {
        $parents = $this->getParentTaxonomies();
        $rootId = Mage::helper('dls_dlsblog/taxonomy')->getRootTaxonomyId();
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

    /**
     * get default values
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getDefaultValues()
    {
        $values = array();
        $values['status'] = 1;
        return $values;
    }
    
}
