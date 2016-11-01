<?php

/**
 * Blog model
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Model_Blogset extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'dls_dlsblog_blogset';
    const CACHE_TAG = 'dls_dlsblog_blogset';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'dls_dlsblog_blogset';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'blogset';
    protected $_taxonomyInstance = null;

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
        $this->_init('dls_dlsblog/blogset');
    }

    /**
     * before save blog setting
     *
     * @access protected
     * @return DLS_DLSBlog_Model_Blogset
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
     * get the url to the blog setting details page
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getBlogsetUrl()
    {
        if ($this->getUrlKey()) {
            $urlKey = '';
            if ($prefix = Mage::getStoreConfig('dls_dlsblog/blogset/url_prefix')) {
                $urlKey .= $prefix.'/';
            }
            $urlKey .= $this->getUrlKey();
            if ($suffix = Mage::getStoreConfig('dls_dlsblog/blogset/url_suffix')) {
                $urlKey .= '.'.$suffix;
            }
            return Mage::getUrl('', array('_direct'=>$urlKey));
        }
        return Mage::getUrl('dls_dlsblog/blogset/view', array('id'=>$this->getId()));
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
     * save blog setting relation
     *
     * @access public
     * @return DLS_DLSBlog_Model_Blogset
     * @author Ultimate Module Creator
     */
    protected function _afterSave()
    {
        $this->getTaxonomyInstance()->saveBlogsetRelation($this);
        return parent::_afterSave();
    }

    /**
     * get taxonomy relation model
     *
     * @access public
     * @return DLS_DLSBlog_Model_Blogset_Taxonomy
     * @author Ultimate Module Creator
     */
    public function getTaxonomyInstance()
    {
        if (!$this->_taxonomyInstance) {
            $this->_taxonomyInstance = Mage::getSingleton('dls_dlsblog/blogset_taxonomy');
        }
        return $this->_taxonomyInstance;
    }

    /**
     * get selected  array
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getSelectedTaxonomies()
    {
        if (!$this->hasSelectedTaxonomies()) {
            $taxonomies = array();
            foreach ($this->getSelectedTaxonomiesCollection() as $taxonomy) {
                $taxonomies[] = $taxonomy;
            }
            $this->setSelectedTaxonomies($taxonomies);
        }
        return $this->getData('selected_taxonomies');
    }

    /**
     * Retrieve collection selected 
     *
     * @access public
     * @return DLS_DLSBlog_Model_Blogset_Taxonomy_Collection
     * @author Ultimate Module Creator
     */
    public function getSelectedTaxonomiesCollection()
    {
        $collection = $this->getTaxonomyInstance()->getTaxonomiesCollection($this);
        return $collection;
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
                        ->addFieldToFilter('blogset_id', $this->getId());
                $this->setData('_filter_collection', $collection);
            }
        }
        return $this->getData('_filter_collection');
    }

    /**
     * Retrieve  collection
     *
     * @access public
     * @return DLS_DLSBlog_Model_Resource_Tag_Collection
     * @author Ultimate Module Creator
     */
    public function getSelectedTagsCollection()
    {
        if (!$this->hasData('_tag_collection')) {
            if (!$this->getId()) {
                return new Varien_Data_Collection();
            } else {
                $collection = Mage::getResourceModel('dls_dlsblog/tag_collection')
                        ->addFieldToFilter('blogset_id', $this->getId());
                $this->setData('_tag_collection', $collection);
            }
        }
        return $this->getData('_tag_collection');
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
                        ->addAttributeToFilter('blogset_id', $this->getId());
                $this->setData('_post_collection', $collection);
            }
        }
        return $this->getData('_post_collection');
    }
    
    /**
     * Retrieve parent 
     *
     * @access public
     * @return null|DLS_DLSBlog_Model_Filter
     * @author Ultimate Module Creator
     */
    public function getParentFilter()
    {
        if (!$this->hasData('_parent_filter')) {
            if (!$this->getCustomDefaultFilter()) {
                return null;
            } else {
                $filter = Mage::getModel('dls_dlsblog/filter')
                    ->load($this->getCustomDefaultFilter());
                if ($filter->getId()) {
                    $this->setData('_parent_filter', $filter);
                } else {
                    $this->setData('_parent_filter', null);
                }
            }
        }
        return $this->getData('_parent_filter');
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
