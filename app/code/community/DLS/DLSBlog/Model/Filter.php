<?php

/**
 * Filter model
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Model_Filter extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'dls_dlsblog_filter';
    const CACHE_TAG = 'dls_dlsblog_filter';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'dls_dlsblog_filter';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'filter';
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
        $this->_init('dls_dlsblog/filter');
    }

    /**
     * before save filter
     *
     * @access protected
     * @return DLS_DLSBlog_Model_Filter
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
     * get the url to the filter details page
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getFilterUrl()
    {
        if ($this->getUrlKey()) {
            $urlKey = '';
            if ($prefix = Mage::getStoreConfig('dls_dlsblog/filter/url_prefix')) {
                $urlKey .= $prefix.'/';
            }
            $urlKey .= $this->getUrlKey();
            if ($suffix = Mage::getStoreConfig('dls_dlsblog/filter/url_suffix')) {
                $urlKey .= '.'.$suffix;
            }
            return Mage::getUrl('', array('_direct'=>$urlKey));
        }
        return Mage::getUrl('dls_dlsblog/filter/view', array('id'=>$this->getId()));
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
     * save filter relation
     *
     * @access public
     * @return DLS_DLSBlog_Model_Filter
     * @author Ultimate Module Creator
     */
    protected function _afterSave()
    {
        $this->getTaxonomyInstance()->saveFilterRelation($this);
        return parent::_afterSave();
    }

    /**
     * get taxonomy relation model
     *
     * @access public
     * @return DLS_DLSBlog_Model_Filter_Taxonomy
     * @author Ultimate Module Creator
     */
    public function getTaxonomyInstance()
    {
        if (!$this->_taxonomyInstance) {
            $this->_taxonomyInstance = Mage::getSingleton('dls_dlsblog/filter_taxonomy');
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
     * @return DLS_DLSBlog_Model_Filter_Taxonomy_Collection
     * @author Ultimate Module Creator
     */
    public function getSelectedTaxonomiesCollection()
    {
        $collection = $this->getTaxonomyInstance()->getTaxonomiesCollection($this);
        return $collection;
    }

    /**
     * Retrieve parent 
     *
     * @access public
     * @return null|DLS_DLSBlog_Model_Blogset
     * @author Ultimate Module Creator
     */
    public function getParentBlogset()
    {
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
        $values['type'] = '1';

        return $values;
    }
    
}
