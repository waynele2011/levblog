<?php

class DLS_Blog_Block_Adminhtml_Taxonomy_Edit_Tab_Filter extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('filter_grid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
        if ($this->getTaxonomy()->getId()) {
            $this->setDefaultFilter(array('in_filters' => 1));
        }
    }

    protected function _prepareCollection() {
        $collection = Mage::getResourceModel('dls_blog/filter_collection');
        if ($this->getTaxonomy()->getId()) {
            $constraint = 'related.taxonomy_id=' . $this->getTaxonomy()->getId();
        } else {
            $constraint = 'related.taxonomy_id=0';
        }
        $collection->getSelect()->joinLeft(
                array('related' => $collection->getTable('dls_blog/taxonomy_filter')), 'related.filter_id=main_table.entity_id AND ' . $constraint, array('position')
        );
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    protected function _prepareMassaction() {
        return $this;
    }

    protected function _prepareColumns() {
        $this->addColumn(
                'in_filters', array(
            'header_css_class' => 'a-center',
            'type' => 'checkbox',
            'name' => 'in_filters',
            'values' => $this->_getSelectedFilters(),
            'align' => 'center',
            'index' => 'entity_id'
                )
        );
        $this->addColumn(
                'name', array(
            'header' => Mage::helper('dls_blog')->__('Name'),
            'align' => 'left',
            'index' => 'name',
            'renderer' => 'dls_blog/adminhtml_helper_column_renderer_relation',
            'params' => array(
                'id' => 'getId'
            ),
            'base_link' => 'adminhtml/blog_filter/edit',
                )
        );
        $this->addColumn(
                'position', array(
            'header' => Mage::helper('dls_blog')->__('Position'),
            'name' => 'position',
            'width' => 60,
            'type' => 'number',
            'validate_class' => 'validate-number',
            'index' => 'position',
            'editable' => true,
                )
        );
    }

    protected function _getSelectedFilters() {
        $filters = $this->getTaxonomyFilters();
        if (!is_array($filters)) {
            $filters = array_keys($this->getSelectedFilters());
        }
        return $filters;
    }

    public function getSelectedFilters() {
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

    public function getRowUrl($item) {
        return '#';
    }

    public function getGridUrl() {
        return $this->getUrl(
                        '*/*/filtersGrid', array(
                    'id' => $this->getTaxonomy()->getId()
                        )
        );
    }

    public function getTaxonomy() {
        return Mage::registry('current_taxonomy');
    }

    protected function _addColumnFilterToCollection($column) {
        // Set custom filter for in product flag
        if ($column->getId() == 'in_filters') {
            $filterIds = $this->_getSelectedFilters();
            if (empty($filterIds)) {
                $filterIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in' => $filterIds));
            } else {
                if ($filterIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin' => $filterIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

}
