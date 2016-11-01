<?php

class DLS_Blog_Block_Adminhtml_Taxonomy_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        $this->_objectId = 'entity_id';
        $this->_blockGroup = 'dls_blog';
        $this->_controller = 'adminhtml_taxonomy';
        $this->_mode = 'edit';
        parent::__construct();
        $this->setTemplate('dls_blog/taxonomy/edit.phtml');
    }

}
