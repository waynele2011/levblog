<?php

class DLS_DLSBlog_Helper_Data extends Mage_Core_Helper_Abstract {

    public function convertOptions($options) {
        $converted = array();
        foreach ($options as $option) {
            if (isset($option['value']) && !is_array($option['value']) &&
                    isset($option['label']) && !is_array($option['label'])) {
                $converted[$option['value']] = $option['label'];
            }
        }
        return $converted;
    }
    
    public function sanitizeStringForUrl($string){
        $string = strtolower($string);
        $string = html_entity_decode($string);
        $string = str_replace(array('δ','ό','φ','ί'),array('ae','ue','oe','ss'),$string);
        $string = preg_replace('#[^\w\sδόφί]#',null,$string);
        $string = preg_replace('#[\s]{2,}#',' ',$string);
        $string = str_replace(array(' '),array('-'),$string);
        return $string;
    }


}
