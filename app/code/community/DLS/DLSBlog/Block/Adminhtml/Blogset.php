<?php

class DLS_DLSBlog_Block_Adminhtml_Blogset extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        $this->_controller = 'adminhtml_blogset';
        $this->_blockGroup = 'dls_dlsblog';
        parent::__construct();
        $this->_headerText = Mage::helper('dls_dlsblog')->__('Blog setting');
        $this->_updateButton('add', 'label', Mage::helper('dls_dlsblog')->__('Add Blog setting'));
    }

}
