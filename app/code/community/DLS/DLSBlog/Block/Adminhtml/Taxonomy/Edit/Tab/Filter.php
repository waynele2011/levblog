<?php

/**
 * category - filter relation edit block
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Adminhtml_Taxonomy_Edit_Tab_Filter extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Set grid params
     *
     * @access protected
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('filter_grid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
        if ($this->getTaxonomy()->getId()) {
            $this->setDefaultFilter(array('in_filters' => 1));
        }
    }

    /**
     * prepare the filter collection
     *
     * @access protected
     * @return DLS_DLSBlog_Block_Adminhtml_Taxonomy_Edit_Tab_Filter
     * @author Ultimate Module Creator
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('dls_dlsblog/filter_collection');
        if ($this->getTaxonomy()->getId()) {
            $constraint = 'related.taxonomy_id='.$this->getTaxonomy()->getId();
        } else {
            $constraint = 'related.taxonomy_id=0';
        }
        $collection->getSelect()->joinLeft(
            array('related' => $collection->getTable('dls_dlsblog/taxonomy_filter')),
            'related.filter_id=main_table.entity_id AND '.$constraint,
            array('position')
        );
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    /**
     * prepare mass action grid
     *
     * @access protected
     * @return DLS_DLSBlog_Block_Adminhtml_Taxonomy_Edit_Tab_Filter
     * @author Ultimate Module Creator
     */
    protected function _prepareMassaction()
    {
        return $this;
    }

    /**
     * prepare the grid columns
     *
     * @access protected
     * @return DLS_DLSBlog_Block_Adminhtml_Taxonomy_Edit_Tab_Filter
     * @author Ultimate Module Creator
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_filters',
            array(
                'header_css_class'  => 'a-center',
                'type'              => 'checkbox',
                'name'              => 'in_filters',
                'values'            => $this->_getSelectedFilters(),
                'align'             => 'center',
                'index'             => 'entity_id'
            )
        );
        $this->addColumn(
            'name',
            array(
                'header'    => Mage::helper('dls_dlsblog')->__('Name'),
                'align'     => 'left',
                'index'     => 'name',
                'renderer'  => 'dls_dlsblog/adminhtml_helper_column_renderer_relation',
                'params'    => array(
                    'id'    => 'getId'
                ),
                'base_link' => 'adminhtml/dlsblog_filter/edit',
            )
        );
        $this->addColumn(
            'position',
            array(
                'header'         => Mage::helper('dls_dlsblog')->__('Position'),
                'name'           => 'position',
                'width'          => 60,
                'type'           => 'number',
                'validate_class' => 'validate-number',
                'index'          => 'position',
                'editable'       => true,
            )
        );
    }

    /**
     * Retrieve selected 
     *
     * @access protected
     * @return array
     * @author Ultimate Module Creator
     */
    protected function _getSelectedFilters()
    {
        $filters = $this->getTaxonomyFilters();
        if (!is_array($filters)) {
            $filters = array_keys($this->getSelectedFilters());
        }
        return $filters;
    }

    /**
     * Retrieve selected {{siblingsLabels}}
     *
     * @access protected
     * @return array
     * @author Ultimate Module Creator
     */
    public function getSelectedFilters()
    {
        $filters = array();
        $selected = Mage::registry('current_taxonomy')->getSelectedFilters();
        if (!is_array($selected)) {
            $selected = array();
        }
        foreach ($selected as $filter) {
            $filters[$filter->getId()] = array('position' => $filter->getPosition());
        }
        return $filters;
    }

    /**
     * get row url
     *
     * @access public
     * @param DLS_DLSBlog_Model_Filter
     * @return string
     * @author Ultimate Module Creator
     */
    public function getRowUrl($item)
    {
        return '#';
    }

    /**
     * get grid url
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getGridUrl()
    {
        return $this->getUrl(
            '*/*/filtersGrid',
            array(
                'id' => $this->getTaxonomy()->getId()
            )
        );
    }

    /**
     * get the current taxonomy
     *
     * @access public
     * @return DLS_DLSBlog_Model_Taxonomy
     * @author Ultimate Module Creator
     */
    public function getTaxonomy()
    {
        return Mage::registry('current_taxonomy');
    }

    /**
     * Add filter
     *
     * @access protected
     * @param object $column
     * @return DLS_DLSBlog_Block_Adminhtml_Taxonomy_Edit_Tab_Filter
     * @author Ultimate Module Creator
     */
    protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in product flag
        if ($column->getId() == 'in_filters') {
            $filterIds = $this->_getSelectedFilters();
            if (empty($filterIds)) {
                $filterIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$filterIds));
            } else {
                if ($filterIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$filterIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
}
