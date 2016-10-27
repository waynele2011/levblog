<?php 

/**
 * Layout design helper
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Helper_Layoutdesign extends Mage_Core_Helper_Abstract
{
    const LAYOUTDESIGN_ROOT_ID = 1;
    /**
     * get the root id
     *
     * @access public
     * @return int
     * @author Ultimate Module Creator
     */
    public function getRootLayoutdesignId()
    {
        return self::LAYOUTDESIGN_ROOT_ID;
    }
}
