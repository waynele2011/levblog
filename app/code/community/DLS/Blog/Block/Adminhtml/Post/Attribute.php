<?php

class DLS_Blog_Block_Adminhtml_Post_Attribute extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        $this->_controller = 'adminhtml_post_attribute';
        $this->_blockGroup = 'dls_blog';
        $this->_headerText = Mage::helper('dls_blog')->__('Manage Post Attributes');
        parent::__construct();
        $this->_updateButton(
                'add', 'label', Mage::helper('dls_blog')->__('Add New Post Attribute')
        );
    }

}
