<?php

/**
 * Filter - Taxonomy relation resource model collection
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Model_Resource_Filter_Taxonomy_Collection extends DLS_DLSBlog_Model_Resource_Taxonomy_Collection
{
    /**
     * remember if fields have been joined
     * @var bool
     */
    protected $_joinedFields = false;

    /**
     * join the link table
     *
     * @access public
     * @return DLS_DLSBlog_Model_Resource_Filter_Taxonomy_Collection
     * @author Ultimate Module Creator
     */
    public function joinFields()
    {
        if (!$this->_joinedFields) {
            $this->getSelect()->join(
                array('related' => $this->getTable('dls_dlsblog/filter_taxonomy')),
                'related.taxonomy_id = main_table.entity_id',
                array('position')
            );
            $this->_joinedFields = true;
        }
        return $this;
    }

    /**
     * add filter filter
     *
     * @access public
     * @param DLS_DLSBlog_Model_Filter | int $filter
     * @return DLS_DLSBlog_Model_Resource_Filter_Taxonomy_Collection
     * @author Ultimate Module Creator
     */
    public function addFilterFilter($filter)
    {
        if ($filter instanceof DLS_DLSBlog_Model_Filter) {
            $filter = $filter->getId();
        }
        if (!$this->_joinedFields) {
            $this->joinFields();
        }
        $this->getSelect()->where('related.filter_id = ?', $filter);
        return $this;
    }
}
