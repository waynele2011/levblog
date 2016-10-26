<?php

/**
 * Tag admin block
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Adminhtml_Tag extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_tag';
        $this->_blockGroup         = 'dls_dlsblog';
        parent::__construct();
        $this->_headerText         = Mage::helper('dls_dlsblog')->__('Tag');
        $this->_updateButton('add', 'label', Mage::helper('dls_dlsblog')->__('Add Tag'));

    }
}
