<?php

/**
 * Blog setting image field renderer helper
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Adminhtml_Blogset_Helper_Image extends Varien_Data_Form_Element_Image
{
    /**
     * get the url of the image
     *
     * @access protected
     * @return string
     * @author Ultimate Module Creator
     */
    protected function _getUrl()
    {
        $url = false;
        if ($this->getValue()) {
            $url = Mage::helper('dls_dlsblog/blogset_image')->getImageBaseUrl().
                $this->getValue();
        }
        return $url;
    }
}
