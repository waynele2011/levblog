<?php

class DLS_DLSBlog_Block_Adminhtml_Post_Comment extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        $this->_controller = 'adminhtml_post_comment';
        $this->_blockGroup = 'dls_dlsblog';
        parent::__construct();
        $this->_headerText = Mage::helper('dls_dlsblog')->__('Post Comments');
        $this->_removeButton('add');
    }

}
