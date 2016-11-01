<?php

/**
 * Blog Filters list block
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Blogset_Filter_List extends DLS_DLSBlog_Block_Filter_List
{
    /**
     * initialize
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $blogset = $this->getBlogset();
        if ($blogset) {
            $this->getFilters()->addFieldToFilter('blogset_id', $blogset->getId());
        }
    }

    /**
     * prepare the layout - actually do nothing
     *
     * @access protected
     * @return DLS_DLSBlog_Block_Blogset_Filter_List
     * @author Ultimate Module Creator
     */
    protected function _prepareLayout()
    {
        return $this;
    }

    /**
     * get the current blog
     *
     * @access public
     * @return DLS_DLSBlog_Model_Blogset
     * @author Ultimate Module Creator
     */
    public function getBlogset()
    {
        return Mage::registry('current_blogset');
    }
}
