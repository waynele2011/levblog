<?php

/**
 * Filter admin block
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Adminhtml_Filter extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_filter';
        $this->_blockGroup         = 'dls_dlsblog';
        parent::__construct();
        $this->_headerText         = Mage::helper('dls_dlsblog')->__('Filter');
        $this->_updateButton('add', 'label', Mage::helper('dls_dlsblog')->__('Add Filter'));

    }
}
