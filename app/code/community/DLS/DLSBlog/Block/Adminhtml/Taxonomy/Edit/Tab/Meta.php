<?php

/**
 * meta information tab
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Adminhtml_Taxonomy_Edit_Tab_Meta extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return DLS_DLSBlog_Block_Adminhtml_Taxonomy_Edit_Tab_Meta
     * @author Ultimate Module Creator
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setFieldNameSuffix('taxonomy');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'taxonomy_meta_form',
            array('legend' => Mage::helper('dls_dlsblog')->__('Meta Information'))
        );
        $fieldset->addField(
            'meta_title',
            'text',
            array(
                'label' => Mage::helper('dls_dlsblog')->__('Meta-title'),
                'name'  => 'meta_title',
            )
        );
        $fieldset->addField(
            'meta_description',
            'textarea',
            array(
                'name'      => 'meta_description',
                'label'     => Mage::helper('dls_dlsblog')->__('Meta-description'),
              )
        );
        $fieldset->addField(
            'meta_keywords',
            'textarea',
            array(
                'name'      => 'meta_keywords',
                'label'     => Mage::helper('dls_dlsblog')->__('Meta-keywords'),
            )
        );
        $form->addValues(Mage::registry('current_taxonomy')->getData());
        return parent::_prepareForm();
    }
}
