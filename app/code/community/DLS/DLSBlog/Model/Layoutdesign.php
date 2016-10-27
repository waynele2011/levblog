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
