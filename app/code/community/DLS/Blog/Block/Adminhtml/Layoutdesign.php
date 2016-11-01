<?php

class DLS_Blog_Block_Adminhtml_Layoutdesign extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        $this->_controller = 'adminhtml_layoutdesign';
        $this->_blockGroup = 'dls_blog';
        parent::__construct();
        $this->_headerText = Mage::helper('dls_blog')->__('Layout Designs');
        $this->_updateButton('add', 'label', Mage::helper('dls_blog')->__('Add Layout Design'));
    }

}
