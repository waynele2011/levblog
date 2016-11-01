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
        parent::__construct();
        $this->_blockGroup = 'dls_dlsblog';
        $this->_controller = 'adminhtml_layoutdesign';
        $this->_updateButton(
            'save',
            'label',
            Mage::helper('dls_dlsblog')->__('Save Layout Design')
        );
        $this->_updateButton(
            'delete',
            'label',
            Mage::helper('dls_dlsblog')->__('Delete Layout Design')
        );
        $this->_addButton(
            'saveandcontinue',
            array(
                'label'   => Mage::helper('dls_dlsblog')->__('Save And Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class'   => 'save',
            ),
            -100
        );
        $this->_formScripts[] = "
            function saveAndContinueEdit() {
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    /**
     * get the edit form header
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getHeaderText()
    {
        if (Mage::registry('current_layoutdesign') && Mage::registry('current_layoutdesign')->getId()) {
            return Mage::helper('dls_dlsblog')->__(
                "Edit Layout Design '%s'",
                $this->escapeHtml(Mage::registry('current_layoutdesign')->getName())
            );
        } else {
            return Mage::helper('dls_dlsblog')->__('Add Layout Design');
        }
    }
}
