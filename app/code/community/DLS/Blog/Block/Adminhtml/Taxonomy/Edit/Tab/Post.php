<?php

class DLS_Blog_Block_Adminhtml_Taxonomy_Edit_Tab_Post extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('post_grid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
        if ($this->getTaxonomy()->getId()) {
            $this->setDefaultFilter(array('in_posts' => 1));
        }
    }

    protected function _prepareCollection() {
        $collection = Mage::getResourceModel('dls_blog/post_collection')->addAttributeToSelect('title');
        if ($this->getTaxonomy()->getId()) {
            $constraint = 'related.taxonomy_id=' . $this->getTaxonomy()->getId();
        } else {
            $constraint = 'related.taxonomy_id=0';
        }
        $collection->getSelect()->joinLeft(
                array('related' => $collection->getTable('dls_blog/taxonomy_post')), 'related.post_id=e.entity_id AND ' . $constraint, array('position')
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
                'in_posts', array(
            'header_css_class' => 'a-center',
            'type' => 'checkbox',
            'name' => 'in_posts',
            'values' => $this->_getSelectedPosts(),
            'align' => 'center',
            'index' => 'entity_id'
                )
        );
        $this->addColumn(
                'title', array(
            'header' => Mage::helper('dls_blog')->__('Title'),
            'align' => 'left',
            'index' => 'title',
            'renderer' => 'dls_blog/adminhtml_helper_column_renderer_relation',
            'params' => array(
                'id' => 'getId'
            ),
            'base_link' => 'adminhtml/blog_post/edit',
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

    protected function _getSelectedPosts() {
        $posts = $this->getTaxonomyPosts();
        if (!is_array($posts)) {
            $posts = array_keys($this->getSelectedPosts());
        }
        return $posts;
    }

    public function getSelectedPosts() {
        $posts = array();
        $selected = Mage::registry('current_taxonomy')->getSelectedPosts();
        if (!is_array($selected)) {
            $selected = array();
        }
        foreach ($selected as $post) {
            $posts[$post->getId()] = array('position' => $post->getPosition());
        }
        return $posts;
    }

    public function getRowUrl($item) {
        return '#';
    }

    public function getGridUrl() {
        return $this->getUrl(
                        '*/*/postsGrid', array(
                    'id' => $this->getTaxonomy()->getId()
                        )
        );
    }

    public function getTaxonomy() {
        return Mage::registry('current_taxonomy');
    }

    protected function _addColumnFilterToCollection($column) {
        // Set custom filter for in product flag
        if ($column->getId() == 'in_posts') {
            $postIds = $this->_getSelectedPosts();
            if (empty($postIds)) {
                $postIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in' => $postIds));
            } else {
                if ($postIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin' => $postIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

}
