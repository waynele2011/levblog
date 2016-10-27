<?php

/**
 * Post collection resource model
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Model_Resource_Post_Collection extends Mage_Catalog_Model_Resource_Collection_Abstract
{
    protected $_joinedFields = array();

    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('dls_dlsblog/post');
    }

    /**
     * get posts as array
     *
     * @access protected
     * @param string $valueField
     * @param string $labelField
     * @param array $additional
     * @return array
     * @author Ultimate Module Creator
     */
    protected function _toOptionArray($valueField='entity_id', $labelField='title', $additional=array())
    {
        $this->addAttributeToSelect('title');
        return parent::_toOptionArray($valueField, $labelField, $additional);
    }

    /**
     * get options hash
     *
     * @access protected
     * @param string $valueField
     * @param string $labelField
     * @return array
     * @author Ultimate Module Creator
     */
    protected function _toOptionHash($valueField='entity_id', $labelField='title')
    {
        $this->addAttributeToSelect('title');
        return parent::_toOptionHash($valueField, $labelField);
    }

    /**
     * add the product filter to collection
     *
     * @access public
     * @param mixed (Mage_Catalog_Model_Product|int) $product
     * @return DLS_DLSBlog_Model_Resource_Post_Collection
     * @author Ultimate Module Creator
     */
    public function addProductFilter($product)
    {
        if ($product instanceof Mage_Catalog_Model_Product) {
            $product = $product->getId();
        }
        if (!isset($this->_joinedFields['product'])) {
            $this->getSelect()->join(
                array('related_product' => $this->getTable('dls_dlsblog/post_product')),
                'related_product.post_id = e.entity_id',
                array('position')
            );
            $this->getSelect()->where('related_product.product_id = ?', $product);
            $this->_joinedFields['product'] = true;
        }
        return $this;
    }

    /**
     * add the taxonomy filter to collection
     *
     * @access public
     * @param mixed (DLS_DLSBlog_Model_Taxonomy|int) $taxonomy
     * @return DLS_DLSBlog_Model_Resource_Post_Collection
     * @author Ultimate Module Creator
     */
    public function addTaxonomyFilter($taxonomy)
    {
        if ($taxonomy instanceof DLS_DLSBlog_Model_Taxonomy) {
            $taxonomy = $taxonomy->getId();
        }
        if (!isset($this->_joinedFields['taxonomy'])) {
            $this->getSelect()->join(
                array('related_taxonomy' => $this->getTable('dls_dlsblog/post_taxonomy')),
                'related_taxonomy.post_id = e.entity_id',
                array('position')
            );
            $this->getSelect()->where('related_taxonomy.taxonomy_id = ?', $taxonomy);
            $this->_joinedFields['taxonomy'] = true;
        }
        return $this;
    }

    /**
     * add the tag filter to collection
     *
     * @access public
     * @param mixed (DLS_DLSBlog_Model_Tag|int) $tag
     * @return DLS_DLSBlog_Model_Resource_Post_Collection
     * @author Ultimate Module Creator
     */
    public function addTagFilter($tag)
    {
        if ($tag instanceof DLS_DLSBlog_Model_Tag) {
            $tag = $tag->getId();
        }
        if (!isset($this->_joinedFields['tag'])) {
            $this->getSelect()->join(
                array('related_tag' => $this->getTable('dls_dlsblog/post_tag')),
                'related_tag.post_id = e.entity_id',
                array('position')
            );
            $this->getSelect()->where('related_tag.tag_id = ?', $tag);
            $this->_joinedFields['tag'] = true;
        }
        return $this;
    }

    /**
     * Get SQL for get record count.
     * Extra GROUP BY strip added.
     *
     * @access public
     * @return Varien_Db_Select
     * @author Ultimate Module Creator
     */
    public function getSelectCountSql()
    {
        $countSelect = parent::getSelectCountSql();
        $countSelect->reset(Zend_Db_Select::GROUP);
        return $countSelect;
    }
}
