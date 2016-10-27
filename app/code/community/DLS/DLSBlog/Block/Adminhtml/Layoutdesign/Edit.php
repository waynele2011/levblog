<?php

/**
 * Layout design admin edit form
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Adminhtml_Layoutdesign_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        $this->_objectId    = 'entity_id';
        $this->_blockGroup  = 'dls_dlsblog';
        $this->_controller  = 'adminhtml_layoutdesign';
        $this->_mode        = 'edit';
        parent::__construct();
        $this->setTemplate('dls_dlsblog/layoutdesign/edit.phtml');
    }
}

