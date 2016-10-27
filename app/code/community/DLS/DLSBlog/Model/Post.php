<?php

/**
 * Post model
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Model_Post extends Mage_Catalog_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'dls_dlsblog_post';
    const CACHE_TAG = 'dls_dlsblog_post';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'dls_dlsblog_post';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'post';
    protected $_taxonomyInstance = null;
    protected $_tagInstance = null;
    protected $_productInstance = null;

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
        $this->_init('dls_dlsblog/post');
    }

    /**
     * before save post
     *
     * @access protected
     * @return DLS_DLSBlog_Model_Post
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
     * get the url to the post details page
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getPostUrl()
    {
        if ($this->getUrlKey()) {
            $urlKey = '';
            if ($prefix = Mage::getStoreConfig('dls_dlsblog/post/url_prefix')) {
                $urlKey .= $prefix.'/';
            }
            $urlKey .= $this->getUrlKey();
            if ($suffix = Mage::getStoreConfig('dls_dlsblog/post/url_suffix')) {
                $urlKey .= '.'.$suffix;
            }
            return Mage::getUrl('', array('_direct'=>$urlKey));
        }
        return Mage::getUrl('dls_dlsblog/post/view', array('id'=>$this->getId()));
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
     * get the post Main content
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getMainContent()
    {
        $main_content = $this->getData('main_content');
        $helper = Mage::helper('cms');
        $processor = $helper->getBlockTemplateProcessor();
        $html = $processor->filter($main_content);
        return $html;
    }

    /**
     * get the post Short content
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getShortContent()
    {
        $short_content = $this->getData('short_content');
        $helper = Mage::helper('cms');
        $processor = $helper->getBlockTemplateProcessor();
        $html = $processor->filter($short_content);
        return $html;
    }

    /**
     * save post relation
     *
     * @access public
     * @return DLS_DLSBlog_Model_Post
     * @author Ultimate Module Creator
     */
    protected function _afterSave()
    {
        $this->getProductInstance()->savePostRelation($this);
        $this->getTaxonomyInstance()->savePostRelation($this);
        $this->getTagInstance()->savePostRelation($this);
        return parent::_afterSave();
    }

    /**
     * get product relation model
     *
     * @access public
     * @return DLS_DLSBlog_Model_Post_Product
     * @author Ultimate Module Creator
     */
    public function getProductInstance()
    {
        if (!$this->_productInstance) {
            $this->_productInstance = Mage::getSingleton('dls_dlsblog/post_product');
        }
        return $this->_productInstance;
    }

    /**
     * get selected products array
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getSelectedProducts()
    {
        if (!$this->hasSelectedProducts()) {
            $products = array();
            foreach ($this->getSelectedProductsCollection() as $product) {
                $products[] = $product;
            }
            $this->setSelectedProducts($products);
        }
        return $this->getData('selected_products');
    }

    /**
     * Retrieve collection selected products
     *
     * @access public
     * @return DLS_DLSBlog_Resource_Post_Product_Collection
     * @author Ultimate Module Creator
     */
    public function getSelectedProductsCollection()
    {
        $collection = $this->getProductInstance()->getProductCollection($this);
        return $collection;
    }

    /**
     * get taxonomy relation model
     *
     * @access public
     * @return DLS_DLSBlog_Model_Post_Taxonomy
     * @author Ultimate Module Creator
     */
    public function getTaxonomyInstance()
    {
        if (!$this->_taxonomyInstance) {
            $this->_taxonomyInstance = Mage::getSingleton('dls_dlsblog/post_taxonomy');
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
     * @return DLS_DLSBlog_Model_Post_Taxonomy_Collection
     * @author Ultimate Module Creator
     */
    public function getSelectedTaxonomiesCollection()
    {
        $collection = $this->getTaxonomyInstance()->getTaxonomiesCollection($this);
        return $collection;
    }

    /**
     * get tag relation model
     *
     * @access public
     * @return DLS_DLSBlog_Model_Post_Tag
     * @author Ultimate Module Creator
     */
    public function getTagInstance()
    {
        if (!$this->_tagInstance) {
            $this->_tagInstance = Mage::getSingleton('dls_dlsblog/post_tag');
        }
        return $this->_tagInstance;
    }

    /**
     * get selected  array
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getSelectedTags()
    {
        if (!$this->hasSelectedTags()) {
            $tags = array();
            foreach ($this->getSelectedTagsCollection() as $tag) {
                $tags[] = $tag;
            }
            $this->setSelectedTags($tags);
        }
        return $this->getData('selected_tags');
    }

    /**
     * Retrieve collection selected 
     *
     * @access public
     * @return DLS_DLSBlog_Model_Post_Tag_Collection
     * @author Ultimate Module Creator
     */
    public function getSelectedTagsCollection()
    {
        $collection = $this->getTagInstance()->getTagsCollection($this);
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
     * Retrieve default attribute set id
     *
     * @access public
     * @return int
     * @author Ultimate Module Creator
     */
    public function getDefaultAttributeSetId()
    {
        return $this->getResource()->getEntityType()->getDefaultAttributeSetId();
    }

    /**
     * get attribute text value
     *
     * @access public
     * @param $attributeCode
     * @return string
     * @author Ultimate Module Creator
     */
    public function getAttributeText($attributeCode)
    {
        $text = $this->getResource()
            ->getAttribute($attributeCode)
            ->getSource()
            ->getOptionText($this->getData($attributeCode));
        if (is_array($text)) {
            return implode(', ', $text);
        }
        return $text;
    }

    /**
     * check if comments are allowed
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getAllowComments()
    {
        if ($this->getData('allow_comment') == DLS_DLSBlog_Model_Adminhtml_Source_Yesnodefault::NO) {
            return false;
        }
        if ($this->getData('allow_comment') == DLS_DLSBlog_Model_Adminhtml_Source_Yesnodefault::YES) {
            return true;
        }
        return Mage::getStoreConfigFlag('dls_dlsblog/post/allow_comment');
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
        $values['allow_comment'] = DLS_DLSBlog_Model_Adminhtml_Source_Yesnodefault::USE_DEFAULT;
        return $values;
    }
    
}
