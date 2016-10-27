<?php

/**
 * post - tag relation edit block
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Adminhtml_Post_Edit_Tab_Tag extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('tag_grid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
        if ($this->getPost()->getId()) {
            $this->setDefaultFilter(array('in_tags' => 1));
        }
    }

    /**
     * prepare the tag collection
     *
     * @access protected
     * @return DLS_DLSBlog_Block_Adminhtml_Post_Edit_Tab_Tag
     * @author Ultimate Module Creator
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('dls_dlsblog/tag_collection');
        if ($this->getPost()->getId()) {
            $constraint = 'related.post_id='.$this->getPost()->getId();
        } else {
            $constraint = 'related.post_id=0';
        }
        $collection->getSelect()->joinLeft(
            array('related' => $collection->getTable('dls_dlsblog/post_tag')),
            'related.tag_id=main_table.entity_id AND '.$constraint,
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
     * @return DLS_DLSBlog_Block_Adminhtml_Post_Edit_Tab_Tag
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
     * @return DLS_DLSBlog_Block_Adminhtml_Post_Edit_Tab_Tag
     * @author Ultimate Module Creator
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_tags',
            array(
                'header_css_class'  => 'a-center',
                'type'              => 'checkbox',
                'name'              => 'in_tags',
                'values'            => $this->_getSelectedTags(),
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
                'base_link' => 'adminhtml/dlsblog_tag/edit',
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
    protected function _getSelectedTags()
    {
        $tags = $this->getPostTags();
        if (!is_array($tags)) {
            $tags = array_keys($this->getSelectedTags());
        }
        return $tags;
    }

    /**
     * Retrieve selected {{siblingsLabels}}
     *
     * @access protected
     * @return array
     * @author Ultimate Module Creator
     */
    public function getSelectedTags()
    {
        $tags = array();
        $selected = Mage::registry('current_post')->getSelectedTags();
        if (!is_array($selected)) {
            $selected = array();
        }
        foreach ($selected as $tag) {
            $tags[$tag->getId()] = array('position' => $tag->getPosition());
        }
        return $tags;
    }

    /**
     * get row url
     *
     * @access public
     * @param DLS_DLSBlog_Model_Tag
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
            '*/*/tagsGrid',
            array(
                'id' => $this->getPost()->getId()
            )
        );
    }

    /**
     * get the current post
     *
     * @access public
     * @return DLS_DLSBlog_Model_Post
     * @author Ultimate Module Creator
     */
    public function getPost()
    {
        return Mage::registry('current_post');
    }

    /**
     * Add filter
     *
     * @access protected
     * @param object $column
     * @return DLS_DLSBlog_Block_Adminhtml_Post_Edit_Tab_Tag
     * @author Ultimate Module Creator
     */
    protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in product flag
        if ($column->getId() == 'in_tags') {
            $tagIds = $this->_getSelectedTags();
            if (empty($tagIds)) {
                $tagIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$tagIds));
            } else {
                if ($tagIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$tagIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
}
