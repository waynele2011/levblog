<?php

class DLS_Blog_Model_Post extends Mage_Catalog_Model_Abstract {

    const ENTITY = 'dls_blog_post';
    const CACHE_TAG = 'dls_blog_post';
    const APPROVED_STATUS = 'approved';

    protected $_eventPrefix = 'dls_blog_post';
    protected $_eventObject = 'post';
    protected $_taxonomyInstance = null;
    protected $_tagInstance = null;
    protected $_productInstance = null;

    public function _construct() {
        parent::_construct();
        $this->_init('dls_blog/post');
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

    public function getPostUrl() {
        if ($this->getUrlKey()) {
            $urlKey = '';
            if ($prefix = Mage::getStoreConfig('dls_blog/post/url_prefix')) {
                $urlKey .= $prefix . '/';
            }
            $urlKey .= $this->getUrlKey();
            if ($suffix = Mage::getStoreConfig('dls_blog/post/url_suffix')) {
                $urlKey .= '.' . $suffix;
            }
            return Mage::getUrl('', array('_direct' => $urlKey));
        }
        return Mage::getUrl('dls_blog/post/view', array('id' => $this->getId()));
    }

    public function checkUrlKey($urlKey, $active = true) {
        return $this->_getResource()->checkUrlKey($urlKey, $active);
    }

    public function getMainContent() {
        $main_content = $this->getData('main_content');
        $helper = Mage::helper('cms');
        $processor = $helper->getBlockTemplateProcessor();
        $html = $processor->filter($main_content);
        return $html;
    }

    public function getShortContent() {
        $short_content = $this->getData('short_content');
        $helper = Mage::helper('cms');
        $processor = $helper->getBlockTemplateProcessor();
        $html = $processor->filter($short_content);
        return $html;
    }

    protected function _afterSave() {
        $this->getProductInstance()->savePostRelation($this);
        $this->getTaxonomyInstance()->savePostRelation($this);
        $this->getTagInstance()->savePostRelation($this);
        return parent::_afterSave();
    }

    public function getProductInstance() {
        if (!$this->_productInstance) {
            $this->_productInstance = Mage::getSingleton('dls_blog/post_product');
        }
        return $this->_productInstance;
    }

    public function getSelectedProducts() {
        if (!$this->hasSelectedProducts()) {
            $products = array();
            foreach ($this->getSelectedProductsCollection() as $product) {
                $products[] = $product;
            }
            $this->setSelectedProducts($products);
        }
        return $this->getData('selected_products');
    }

    public function getSelectedProductsCollection() {
        $collection = $this->getProductInstance()->getProductCollection($this);
        return $collection;
    }

    public function getTaxonomyInstance() {
        if (!$this->_taxonomyInstance) {
            $this->_taxonomyInstance = Mage::getSingleton('dls_blog/post_taxonomy');
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

    public function getTagInstance() {
        if (!$this->_tagInstance) {
            $this->_tagInstance = Mage::getSingleton('dls_blog/post_tag');
        }
        return $this->_tagInstance;
    }

    public function getSelectedTags() {
        if (!$this->hasSelectedTags()) {
            $tags = array();
            foreach ($this->getSelectedTagsCollection() as $tag) {
                $tags[] = $tag;
            }
            $this->setSelectedTags($tags);
        }
        return $this->getData('selected_tags');
    }

    public function getSelectedTagsCollection() {
        $collection = $this->getTagInstance()->getTagsCollection($this);
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

    public function getDefaultAttributeSetId() {
        return $this->getResource()->getEntityType()->getDefaultAttributeSetId();
    }

    public function getAttributeText($attributeCode) {
        $text = $this->getResource()
                ->getAttribute($attributeCode)
                ->getSource()
                ->getOptionText($this->getData($attributeCode));
        if (is_array($text)) {
            return implode(', ', $text);
        }
        return $text;
    }

    public function getAllowComments() {
        if ($this->getData('allow_comment') == DLS_Blog_Model_Adminhtml_Source_Yesnodefault::NO) {
            return false;
        }
        if ($this->getData('allow_comment') == DLS_Blog_Model_Adminhtml_Source_Yesnodefault::YES) {
            return true;
        }
        return Mage::getStoreConfigFlag('dls_blog/post/allow_comment');
    }

    public function getDefaultValues() {
        $values = array();
        $values['status'] = 1;
        $values['allow_comment'] = DLS_Blog_Model_Adminhtml_Source_Yesnodefault::USE_DEFAULT;
        return $values;
    }

}
