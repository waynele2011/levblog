<?php

/**
 * Blog Taxonomies list block
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Blogset_Taxonomy_List extends DLS_DLSBlog_Block_Taxonomy_List
{
    /**
     * initialize
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $blogset = $this->getBlogset();
         if ($blogset) {
             $this->getTaxonomies()->addBlogsetFilter($blogset->getId());
             $this->getTaxonomies()->unshiftOrder('related_blogset.position', 'ASC');
         }
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return DLS_DLSBlog_Block_Blogset_Taxonomy_List
     * @author Ultimate Module Creator
     */
    protected function _prepareLayout()
    {
        return $this;
    }

    /**
     * get the current blogset
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
