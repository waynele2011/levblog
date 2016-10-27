<?php

/**
 * Layout design model
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Model_Layoutdesign extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'dls_dlsblog_layoutdesign';
    const CACHE_TAG = 'dls_dlsblog_layoutdesign';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'dls_dlsblog_layoutdesign';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'layoutdesign';

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
        $this->_init('dls_dlsblog/layoutdesign');
    }

    /**
     * before save layout design
     *
     * @access protected
     * @return DLS_DLSBlog_Model_Layoutdesign
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
     * save layout design relation
     *
     * @access public
     * @return DLS_DLSBlog_Model_Layoutdesign
     * @author Ultimate Module Creator
     */
    protected function _afterSave()
    {
        return parent::_afterSave();
    }

    /**
     * Retrieve  collection
     *
     * @access public
     * @return DLS_DLSBlog_Model_Resource_Blogset_Collection
     * @author Ultimate Module Creator
     */
    public function getSelectedBlogsetsCollection()
    {
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

    /**
     * Retrieve  collection
     *
     * @access public
     * @return DLS_DLSBlog_Model_Resource_Taxonomy_Collection
     * @author Ultimate Module Creator
     */
    public function getSelectedTaxonomiesCollection()
    {
        if (!$this->hasData('_taxonomy_collection')) {
            if (!$this->getId()) {
                return new Varien_Data_Collection();
            } else {
                $collection = Mage::getResourceModel('dls_dlsblog/taxonomy_collection')
                        ->addFieldToFilter('layoutdesign_id', $this->getId());
                $this->setData('_taxonomy_collection', $collection);
            }
        }
        return $this->getData('_taxonomy_collection');
    }

    /**
     * Retrieve  collection
     *
     * @access public
     * @return DLS_DLSBlog_Model_Resource_Filter_Collection
     * @author Ultimate Module Creator
     */
    public function getSelectedFiltersCollection()
    {
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

    /**
     * Retrieve  collection
     *
     * @access public
     * @return DLS_DLSBlog_Model_Resource_Post_Collection
     * @author Ultimate Module Creator
     */
    public function getSelectedPostsCollection()
    {
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

    /**
     * get the tree model
     *
     * @access public
     * @return DLS_DLSBlog_Model_Resource_Layoutdesign_Tree
     * @author Ultimate Module Creator
     */
    public function getTreeModel()
    {
        return Mage::getResourceModel('dls_dlsblog/layoutdesign_tree');
    }

    /**
     * get tree model instance
     *
     * @access public
     * @return DLS_DLSBlog_Model_Resource_Layoutdesign_Tree
     * @author Ultimate Module Creator
     */
    public function getTreeModelInstance()
    {
        if (is_null($this->_treeModel)) {
            $this->_treeModel = Mage::getResourceSingleton('dls_dlsblog/layoutdesign_tree');
        }
        return $this->_treeModel;
    }

    /**
     * Move layout design
     *
     * @access public
     * @param   int $parentId new parent layout design id
     * @param   int $afterLayoutdesignId layout design id after which we have put current layout design
     * @return  DLS_DLSBlog_Model_Layoutdesign
     * @author Ultimate Module Creator
     */
    public function move($parentId, $afterLayoutdesignId)
    {
        $parent = Mage::getModel('dls_dlsblog/layoutdesign')->load($parentId);
        if (!$parent->getId()) {
            Mage::throwException(
                Mage::helper('dls_dlsblog')->__(
                    'Layout design move operation is not possible: the new parent layout design was not found.'
                )
            );
        }
        if (!$this->getId()) {
            Mage::throwException(
                Mage::helper('dls_dlsblog')->__(
                    'Layout design move operation is not possible: the current layout design was not found.'
                )
            );
        } elseif ($parent->getId() == $this->getId()) {
            Mage::throwException(
                Mage::helper('dls_dlsblog')->__(
                    'Layout design move operation is not possible: parent layout design is equal to child layout design.'
                )
            );
        }
        $this->setMovedLayoutdesignId($this->getId());
        $eventParams = array(
            $this->_eventObject => $this,
            'parent'            => $parent,
            'layoutdesign_id'     => $this->getId(),
            'prev_parent_id'    => $this->getParentId(),
            'parent_id'         => $parentId
        );
        $moveComplete = false;
        $this->_getResource()->beginTransaction();
        try {
            $this->getResource()->changeParent($this, $parent, $afterLayoutdesignId);
            $this->_getResource()->commit();
            $this->setAffectedLayoutdesignIds(array($this->getId(), $this->getParentId(), $parentId));
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
     * Get the parent layout design
     *
     * @access public
     * @return  DLS_DLSBlog_Model_Layoutdesign
     * @author Ultimate Module Creator
     */
    public function getParentLayoutdesign()
    {
        if (!$this->hasData('parent_layoutdesign')) {
            $this->setData(
                'parent_layoutdesign',
                Mage::getModel('dls_dlsblog/layoutdesign')->load($this->getParentId())
            );
        }
        return $this->_getData('parent_layoutdesign');
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
     * Get all parent layout designs ids
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
     * Get all layout designs children
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
     * Get all layout designs children
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getChildLayoutdesigns()
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
     * Get array layout designs ids which are part of layout design path
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
     * Verify layout design ids
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
     * check if layout design has children
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
     * check if layout design can be deleted
     *
     * @access protected
     * @return DLS_DLSBlog_Model_Layoutdesign
     * @author Ultimate Module Creator
     */
    protected function _beforeDelete()
    {
        if ($this->getResource()->isForbiddenToDelete($this->getId())) {
            Mage::throwException(Mage::helper('dls_dlsblog')->__("Can't delete root layout design."));
        }
        return parent::_beforeDelete();
    }

    /**
     * get the layout designs
     *
     * @access public
     * @param DLS_DLSBlog_Model_Layoutdesign $parent
     * @param int $recursionLevel
     * @param bool $sorted
     * @param bool $asCollection
     * @param bool $toLoad
     * @author Ultimate Module Creator
     */
    public function getLayoutdesigns($parent, $recursionLevel = 0, $sorted=false, $asCollection=false, $toLoad=true)
    {
        return $this->getResource()->getLayoutdesigns($parent, $recursionLevel, $sorted, $asCollection, $toLoad);
    }

    /**
     * Return parent layout designs of current layout design
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getParentLayoutdesigns()
    {
        return $this->getResource()->getParentLayoutdesigns($this);
    }

    /**
     * Return children layout designs of current layout design
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getChildrenLayoutdesigns()
    {
        return $this->getResource()->getChildrenLayoutdesigns($this);
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
        $parents = $this->getParentLayoutdesigns();
        $rootId = Mage::helper('dls_dlsblog/layoutdesign')->getRootLayoutdesignId();
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
        $values['basic_layout'] = '1';

        return $values;
    }
    
}
