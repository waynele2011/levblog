<?php

/**
 * DLSBlog default helper
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * convert array to options
     *
     * @access public
     * @param $options
     * @return array
     * @author Ultimate Module Creator
     */
    public function convertOptions($options)
    {
        $converted = array();
        foreach ($options as $option) {
            if (isset($option['value']) && !is_array($option['value']) &&
                isset($option['label']) && !is_array($option['label'])) {
                $converted[$option['value']] = $option['label'];
            }
        }
        return $converted;
    }
}
