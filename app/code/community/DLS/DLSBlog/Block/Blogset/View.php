<?php

/**
 * Blog setting view block
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Blogset_View extends Mage_Core_Block_Template
{
    /**
     * get the current blog setting
     *
     * @access public
     * @return mixed (DLS_DLSBlog_Model_Blogset|null)
     * @author Ultimate Module Creator
     */
    public function getCurrentBlogset()
    {
        return Mage::registry('current_blogset');
    }
}
