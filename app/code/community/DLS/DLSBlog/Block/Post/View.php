<?php

/**
 * Post view block
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Post_View extends Mage_Core_Block_Template
{
    /**
     * get the current post
     *
     * @access public
     * @return mixed (DLS_DLSBlog_Model_Post|null)
     * @author Ultimate Module Creator
     */
    public function getCurrentPost()
    {
        return Mage::registry('current_post');
    }
}
