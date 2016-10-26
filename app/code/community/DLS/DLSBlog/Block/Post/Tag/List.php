<?php

/**
 * Post Tags list block
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Post_Tag_List extends DLS_DLSBlog_Block_Tag_List
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
        $post = $this->getPost();
         if ($post) {
             $this->getTags()->addPostFilter($post->getId());
             $this->getTags()->unshiftOrder('related_post.position', 'ASC');
         }
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return DLS_DLSBlog_Block_Post_Tag_List
     * @author Ultimate Module Creator
     */
    protected function _prepareLayout()
    {
        return $this;
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
}
