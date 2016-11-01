<?php

class DLS_Blog_Model_Layoutdesign_Attribute_Source_Basiclayout extends Mage_Eav_Model_Entity_Attribute_Source_Table {

    public function getAllOptions($withEmpty = true, $defaultValues = false) {
        $options = array(
            array(
                'label' => Mage::helper('dls_blog')->__('1column'),
                'value' => 1
            ),
            array(
                'label' => Mage::helper('dls_blog')->__('2columns-left'),
                'value' => 2
            ),
            array(
                'label' => Mage::helper('dls_blog')->__('2columns-right'),
                'value' => 3
            ),
            array(
                'label' => Mage::helper('dls_blog')->__('3columns'),
                'value' => 4
            ),
            array(
                'label' => Mage::helper('dls_blog')->__('empty'),
                'value' => 5
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
