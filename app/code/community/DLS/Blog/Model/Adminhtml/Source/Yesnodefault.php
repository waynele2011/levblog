<?php

class DLS_Blog_Model_Adminhtml_Source_Yesnodefault extends Mage_Eav_Model_Entity_Attribute_Source_Abstract {

    const YES = 1;
    const NO = 0;
    const USE_DEFAULT = 2;

    public function toOptionArray() {
        return array(
            array(
                'label' => Mage::helper('dls_blog')->__('Use default config'),
                'value' => self::USE_DEFAULT
            ),
            array(
                'label' => Mage::helper('dls_blog')->__('Yes'),
                'value' => self::YES
            ),
            array(
                'label' => Mage::helper('dls_blog')->__('No'),
                'value' => self::NO
            )
        );
    }

    public function getAllOptions() {
        return $this->toOptionArray();
    }

}
