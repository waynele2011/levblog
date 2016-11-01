<?php

class DLS_Blog_Block_Adminhtml_Filter extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        $this->_controller = 'adminhtml_filter';
        $this->_blockGroup = 'dls_blog';
        parent::__construct();
        $this->_headerText = Mage::helper('dls_blog')->__('Filter');
        $this->_updateButton('add', 'label', Mage::helper('dls_blog')->__('Add Filter'));
    }

}
