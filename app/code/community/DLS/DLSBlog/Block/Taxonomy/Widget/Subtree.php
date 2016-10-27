<?php

/**
 * Taxonomy subtree block
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Taxonomy_Widget_Subtree extends DLS_DLSBlog_Block_Taxonomy_List implements
    Mage_Widget_Block_Interface
{
    protected $_template = 'dls_dlsblog/taxonomy/widget/subtree.phtml';
    /**
     * prepare the layout
     *
     * @access protected
     * @return DLS_DLSBlog_Block_Taxonomy_Widget_Subtree
     * @author Ultimate Module Creator
     */
    protected function _prepareLayout()
    {
        $this->getTaxonomies()->addFieldToFilter('entity_id', $this->getTaxonomyId());
        return $this;
    }

    /**
     * get the display mode
     *
     * @access protected
     * @return int
     * @author Ultimate Module Creator
     */
    protected function _getDisplayMode()
    {
        return 1;
    }

    /**
     * get the element id
     *
     * @access protected
     * @return int
     * @author Ultimate Module Creator
     */
    public function getUniqueId()
    {
        if (!$this->getData('uniq_id')) {
            $this->setData('uniq_id', uniqid('subtree'));
        }
        return $this->getData('uniq_id');
    }
}
