<?php

/**
 * Filter view block
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Filter_View extends Mage_Core_Block_Template
{
    /**
     * get the current filter
     *
     * @access public
     * @return mixed (DLS_DLSBlog_Model_Filter|null)
     * @author Ultimate Module Creator
     */
    public function getCurrentFilter()
    {
        return Mage::registry('current_filter');
    }
}
