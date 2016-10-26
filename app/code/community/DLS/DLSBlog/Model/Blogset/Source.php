<?php

class DLS_DLSBlog_Model_Blogset_Source extends Mage_Eav_Model_Entity_Attribute_Source_Abstract {

    public function getAllOptions($withEmpty = false) {
        if (is_null($this->_options)) {
            $this->_options = Mage::getResourceModel('dls_dlsblog/blogset_collection')
                    ->load()
                    ->toOptionArray();
        }
        $options = $this->_options;
        if ($withEmpty) {
            array_unshift($options, array('value' => '', 'label' => ''));
        }
        return $options;
    }

    public function getOptionText($value) {
        $options = $this->getAllOptions(false);
        foreach ($options as $item) {
            if ($item['value'] == $value) {
                return $item['label'];
            }
        }
        return false;
    }

    public function toOptionArray() {
        return $this->getAllOptions();
    }

    public function getFlatColums() {
        $attributeCode = $this->getAttribute()->getAttributeCode();
        $column = array(
            'unsigned' => true,
            'default' => null,
            'extra' => null
        );
        if (Mage::helper('core')->useDbCompatibleMode()) {
            $column['type'] = 'int';
            $column['is_null'] = true;
        } else {
            $column['type'] = Varien_Db_Ddl_Table::TYPE_INTEGER;
            $column['nullable'] = true;
            $column['comment'] = $attributeCode . ' Blog setting column';
        }
        return array($attributeCode => $column);
    }

    public function getFlatUpdateSelect($store) {
        return Mage::getResourceModel('eav/entity_attribute_option')
                        ->getFlatUpdateSelect($this->getAttribute(), $store, false);
    }

}
