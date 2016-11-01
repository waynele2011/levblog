<?php

/**
 * Post admin block
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Adminhtml_Post extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_post';
        $this->_blockGroup         = 'dls_dlsblog';
        parent::__construct();
        $this->_headerText         = Mage::helper('dls_dlsblog')->__('Manager Posts');
        $this->_updateButton('add', 'label', Mage::helper('dls_dlsblog')->__('Add Post'));

        $this->setTemplate('dls_dlsblog/grid.phtml');
    }
}
