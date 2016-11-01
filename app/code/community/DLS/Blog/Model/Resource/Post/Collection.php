<?php

class DLS_Blog_Model_Resource_Post_Collection extends Mage_Catalog_Model_Resource_Collection_Abstract {

    protected $_joinedFields = array();

    protected function _construct() {
        parent::_construct();
        $this->_init('dls_blog/post');
    }

    protected function _toOptionArray($valueField = 'entity_id', $labelField = 'title', $additional = array()) {
        $this->addAttributeToSelect('title');
        return parent::_toOptionArray($valueField, $labelField, $additional);
    }

    protected function _toOptionHash($valueField = 'entity_id', $labelField = 'title') {
        $this->addAttributeToSelect('title');
        return parent::_toOptionHash($valueField, $labelField);
    }

    public function addProductFilter($product) {
        if ($product instanceof Mage_Catalog_Model_Product) {
            $product = $product->getId();
        }
        if (!isset($this->_joinedFields['product'])) {
            $this->getSelect()->join(
                    array('related_product' => $this->getTable('dls_blog/post_product')), 'related_product.post_id = e.entity_id', array('position')
            );
            $this->getSelect()->where('related_product.product_id = ?', $product);
            $this->_joinedFields['product'] = true;
        }
        return $this;
    }

    public function addTaxonomyFilter($taxonomy) {
        if ($taxonomy instanceof DLS_Blog_Model_Taxonomy) {
            $taxonomy = $taxonomy->getId();
        }
        if (!isset($this->_joinedFields['taxonomy'])) {
            $this->getSelect()->join(
                    array('related_taxonomy' => $this->getTable('dls_blog/post_taxonomy')), 'related_taxonomy.post_id = e.entity_id', array('position')
            );
            $this->getSelect()->where('related_taxonomy.taxonomy_id = ?', $taxonomy);
            $this->_joinedFields['taxonomy'] = true;
        }
        return $this;
    }

    public function addTagFilter($tag) {
        if ($tag instanceof DLS_Blog_Model_Tag) {
            $tag = $tag->getId();
        }
        if (!isset($this->_joinedFields['tag'])) {
            $this->getSelect()->join(
                    array('related_tag' => $this->getTable('dls_blog/post_tag')), 'related_tag.post_id = e.entity_id', array('position')
            );
            $this->getSelect()->where('related_tag.tag_id = ?', $tag);
            $this->_joinedFields['tag'] = true;
        }
        return $this;
    }

    public function getSelectCountSql() {
        $countSelect = parent::getSelectCountSql();
        $countSelect->reset(Zend_Db_Select::GROUP);
        return $countSelect;
    }

}
