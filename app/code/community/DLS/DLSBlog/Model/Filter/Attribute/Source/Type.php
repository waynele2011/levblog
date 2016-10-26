<?php

class DLS_DLSBlog_Model_Filter_Attribute_Source_Type extends Mage_Eav_Model_Entity_Attribute_Source_Table {

    public function getAllOptions($withEmpty = true, $defaultValues = false) {
        $options = array(
            array(
                'label' => Mage::helper('dls_dlsblog')->__('conditions'),
                'value' => 1
            ),
            array(
                'label' => Mage::helper('dls_dlsblog')->__('items list'),
                'value' => 2
            ),
        );
        if ($withEmpty) {
            array_unshift($options, array('label' => '', 'value' => ''));
        }
        return $options;
    }

    public function getOptionsArray($withEmpty = true) {
        $options = array();
        foreach ($this->getAllOptions($withEmpty) as $option) {
            $options[$option['value']] = $option['label'];
        }
        return $options;
    }

    public function getOptionText($value) {
        $options = $this->getOptionsArray();
        if (!is_array($value)) {
            $value = explode(',', $value);
        }
        $texts = array();
        foreach ($value as $v) {
            if (isset($options[$v])) {
                $texts[] = $options[$v];
            }
        }
        return implode(', ', $texts);
    }

}
