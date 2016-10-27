<?php

/**
 * Filter collection resource model
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Model_Resource_Filter_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
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
        $this->_init('dls_dlsblog/filter');
    }

    /**
     * get filters as array
     *
     * @access protected
     * @param string $valueField
     * @param string $labelField
     * @param array $additional
     * @return array
     * @author Ultimate Module Creator
     */
    protected function _toOptionArray($valueField='entity_id', $labelField='name', $additional=array())
    {
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
    protected function _toOptionHash($valueField='entity_id', $labelField='name')
    {
        return parent::_toOptionHash($valueField, $labelField);
    }

    /**
     * add the taxonomy filter to collection
     *
     * @access public
     * @param mixed (DLS_DLSBlog_Model_Taxonomy|int) $taxonomy
     * @return DLS_DLSBlog_Model_Resource_Filter_Collection
     * @author Ultimate Module Creator
     */
    public function addTaxonomyFilter($taxonomy)
    {
        if ($taxonomy instanceof DLS_DLSBlog_Model_Taxonomy) {
            $taxonomy = $taxonomy->getId();
        }
        if (!isset($this->_joinedFields['taxonomy'])) {
            $this->getSelect()->join(
                array('related_taxonomy' => $this->getTable('dls_dlsblog/filter_taxonomy')),
                'related_taxonomy.filter_id = main_table.entity_id',
                array('position')
            );
            $this->getSelect()->where('related_taxonomy.taxonomy_id = ?', $taxonomy);
            $this->_joinedFields['taxonomy'] = true;
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
