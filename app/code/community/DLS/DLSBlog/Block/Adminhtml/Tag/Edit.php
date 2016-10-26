<?php

/**
 * Tag admin edit form
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Adminhtml_Tag_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
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
        $this->_controller = 'adminhtml_tag';
        $this->_updateButton(
            'save',
            'label',
            Mage::helper('dls_dlsblog')->__('Save Tag')
        );
        $this->_updateButton(
            'delete',
            'label',
            Mage::helper('dls_dlsblog')->__('Delete Tag')
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
        if (Mage::registry('current_tag') && Mage::registry('current_tag')->getId()) {
            return Mage::helper('dls_dlsblog')->__(
                "Edit Tag '%s'",
                $this->escapeHtml(Mage::registry('current_tag')->getName())
            );
        } else {
            return Mage::helper('dls_dlsblog')->__('Add Tag');
        }
    }
}
