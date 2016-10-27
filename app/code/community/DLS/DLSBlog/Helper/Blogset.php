<?php 

/**
 * Blog setting helper
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Helper_Blogset extends Mage_Core_Helper_Abstract
{

    /**
     * check if breadcrumbs can be used
     *
     * @access public
     * @return bool
     * @author Ultimate Module Creator
     */
    public function getUseBreadcrumbs()
    {
        return Mage::getStoreConfigFlag('dls_dlsblog/blogset/breadcrumbs');
    }
}
