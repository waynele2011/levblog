<?php

class DLS_DLSBlog_Block_Adminhtml_Post_Attribute extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        $this->_controller = 'adminhtml_post_attribute';
        $this->_blockGroup = 'dls_dlsblog';
        $this->_headerText = Mage::helper('dls_dlsblog')->__('Manage Post Attributes');
        parent::__construct();
        $this->_updateButton(
                'add', 'label', Mage::helper('dls_dlsblog')->__('Add New Post Attribute')
        );
    }

}
