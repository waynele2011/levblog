<?php

class DLS_Blog_Block_Adminhtml_Post_Attribute_Grid extends Mage_Eav_Block_Adminhtml_Attribute_Grid_Abstract {

    protected function _prepareCollection() {
        $collection = Mage::getResourceModel('dls_blog/post_attribute_collection')
                ->addVisibleFilter();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        parent::_prepareColumns();
        $this->addColumnAfter(
                'is_global', array(
            'header' => Mage::helper('dls_blog')->__('Scope'),
            'sortable' => true,
            'index' => 'is_global',
            'type' => 'options',
            'options' => array(
                Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE =>
                Mage::helper('dls_blog')->__('Store View'),
                Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE =>
                Mage::helper('dls_blog')->__('Website'),
                Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL =>
                Mage::helper('dls_blog')->__('Global'),
            ),
            'align' => 'center',
                ), 'is_user_defined'
        );
        return $this;
    }

}
