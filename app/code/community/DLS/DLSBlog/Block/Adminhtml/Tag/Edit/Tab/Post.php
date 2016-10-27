<?php

/**
 * tag - post relation edit block
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Adminhtml_Tag_Edit_Tab_Post extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('post_grid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
        if ($this->getTag()->getId()) {
            $this->setDefaultFilter(array('in_posts' => 1));
        }
    }

    /**
     * prepare the post collection
     *
     * @access protected
     * @return DLS_DLSBlog_Block_Adminhtml_Tag_Edit_Tab_Post
     * @author Ultimate Module Creator
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('dls_dlsblog/post_collection')->addAttributeToSelect('title');
        if ($this->getTag()->getId()) {
            $constraint = 'related.tag_id='.$this->getTag()->getId();
        } else {
            $constraint = 'related.tag_id=0';
        }
        $collection->getSelect()->joinLeft(
            array('related' => $collection->getTable('dls_dlsblog/tag_post')),
            'related.post_id=e.entity_id AND '.$constraint,
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
     * @return DLS_DLSBlog_Block_Adminhtml_Tag_Edit_Tab_Post
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
     * @return DLS_DLSBlog_Block_Adminhtml_Tag_Edit_Tab_Post
     * @author Ultimate Module Creator
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_posts',
            array(
                'header_css_class'  => 'a-center',
                'type'              => 'checkbox',
                'name'              => 'in_posts',
                'values'            => $this->_getSelectedPosts(),
                'align'             => 'center',
                'index'             => 'entity_id'
            )
        );
        $this->addColumn(
            'title',
            array(
                'header'    => Mage::helper('dls_dlsblog')->__('Title'),
                'align'     => 'left',
                'index'     => 'title',
                'renderer'  => 'dls_dlsblog/adminhtml_helper_column_renderer_relation',
                'params'    => array(
                    'id'    => 'getId'
                ),
                'base_link' => 'adminhtml/dlsblog_post/edit',
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
    protected function _getSelectedPosts()
    {
        $posts = $this->getTagPosts();
        if (!is_array($posts)) {
            $posts = array_keys($this->getSelectedPosts());
        }
        return $posts;
    }

    /**
     * Retrieve selected {{siblingsLabels}}
     *
     * @access protected
     * @return array
     * @author Ultimate Module Creator
     */
    public function getSelectedPosts()
    {
        $posts = array();
        $selected = Mage::registry('current_tag')->getSelectedPosts();
        if (!is_array($selected)) {
            $selected = array();
        }
        foreach ($selected as $post) {
            $posts[$post->getId()] = array('position' => $post->getPosition());
        }
        return $posts;
    }

    /**
     * get row url
     *
     * @access public
     * @param DLS_DLSBlog_Model_Post
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
            '*/*/postsGrid',
            array(
                'id' => $this->getTag()->getId()
            )
        );
    }

    /**
     * get the current tag
     *
     * @access public
     * @return DLS_DLSBlog_Model_Tag
     * @author Ultimate Module Creator
     */
    public function getTag()
    {
        return Mage::registry('current_tag');
    }

    /**
     * Add filter
     *
     * @access protected
     * @param object $column
     * @return DLS_DLSBlog_Block_Adminhtml_Tag_Edit_Tab_Post
     * @author Ultimate Module Creator
     */
    protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in product flag
        if ($column->getId() == 'in_posts') {
            $postIds = $this->_getSelectedPosts();
            if (empty($postIds)) {
                $postIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$postIds));
            } else {
                if ($postIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$postIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
}
