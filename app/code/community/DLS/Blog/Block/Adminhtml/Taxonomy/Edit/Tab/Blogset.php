<?php

class DLS_Blog_Block_Adminhtml_Taxonomy_Edit_Tab_Blogset extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('blogset_grid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
        if ($this->getTaxonomy()->getId()) {
            $this->setDefaultFilter(array('in_blogsets' => 1));
        }
    }

    protected function _prepareCollection() {
        $collection = Mage::getResourceModel('dls_blog/blogset_collection');
        if ($this->getTaxonomy()->getId()) {
            $constraint = 'related.taxonomy_id=' . $this->getTaxonomy()->getId();
        } else {
            $constraint = 'related.taxonomy_id=0';
        }
        $collection->getSelect()->joinLeft(
                array('related' => $collection->getTable('dls_blog/taxonomy_blogset')), 'related.blogset_id=main_table.entity_id AND ' . $constraint, array('position')
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
                'in_blogsets', array(
            'header_css_class' => 'a-center',
            'type' => 'checkbox',
            'name' => 'in_blogsets',
            'values' => $this->_getSelectedBlogsets(),
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
            'base_link' => 'adminhtml/blog_blogset/edit',
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

    protected function _getSelectedBlogsets() {
        $blogsets = $this->getTaxonomyBlogsets();
        if (!is_array($blogsets)) {
            $blogsets = array_keys($this->getSelectedBlogsets());
        }
        return $blogsets;
    }

    public function getSelectedBlogsets() {
        $blogsets = array();
        $selected = Mage::registry('current_taxonomy')->getSelectedBlogsets();
        if (!is_array($selected)) {
            $selected = array();
        }
        foreach ($selected as $blogset) {
            $blogsets[$blogset->getId()] = array('position' => $blogset->getPosition());
        }
        return $blogsets;
    }

    public function getRowUrl($item) {
        return '#';
    }

    public function getGridUrl() {
        return $this->getUrl(
                        '*/*/blogsetsGrid', array(
                    'id' => $this->getTaxonomy()->getId()
                        )
        );
    }

    public function getTaxonomy() {
        return Mage::registry('current_taxonomy');
    }

    protected function _addColumnFilterToCollection($column) {
        // Set custom filter for in product flag
        if ($column->getId() == 'in_blogsets') {
            $blogsetIds = $this->_getSelectedBlogsets();
            if (empty($blogsetIds)) {
                $blogsetIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in' => $blogsetIds));
            } else {
                if ($blogsetIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin' => $blogsetIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

}
