<?php

/**
 * Layout design edit form tab
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Adminhtml_Layoutdesign_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return DLS_DLSBlog_Block_Adminhtml_Layoutdesign_Edit_Tab_Form
     * @author Ultimate Module Creator
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('layoutdesign_');
        $form->setFieldNameSuffix('layoutdesign');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'layoutdesign_form',
            array('legend' => Mage::helper('dls_dlsblog')->__('Layout design'))
        );
        if (!$this->getLayoutdesign()->getId()) {
            $parentId = $this->getRequest()->getParam('parent');
            if (!$parentId) {
                $parentId = Mage::helper('dls_dlsblog/layoutdesign')->getRootLayoutdesignId();
            }
            $fieldset->addField(
                'path',
                'hidden',
                array(
                    'name'  => 'path',
                    'value' => $parentId
                )
            );
        } else {
            $fieldset->addField(
                'id',
                'hidden',
                array(
                    'name'  => 'id',
                    'value' => $this->getLayoutdesign()->getId()
                )
            );
            $fieldset->addField(
                'path',
                'hidden',
                array(
                    'name'  => 'path',
                    'value' => $this->getLayoutdesign()->getPath()
                )
            );
        }

        $fieldset->addField(
            'name',
            'text',
            array(
                'label' => Mage::helper('dls_dlsblog')->__('Name'),
                'name'  => 'name',
                'required'  => true,
                'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'description',
            'textarea',
            array(
                'label' => Mage::helper('dls_dlsblog')->__('Description'),
                'name'  => 'description',

           )
        );

        $fieldset->addField(
            'basic_layout',
            'select',
            array(
                'label' => Mage::helper('dls_dlsblog')->__('Basic layout'),
                'name'  => 'basic_layout',
                'required'  => true,
                'class' => 'required-entry',

                'values'=> Mage::getModel('dls_dlsblog/layoutdesign_attribute_source_basiclayout')->getAllOptions(true),
           )
        );

        $fieldset->addField(
            'design_code',
            'textarea',
            array(
                'label' => Mage::helper('dls_dlsblog')->__('Design frame'),
                'name'  => 'design_code',

           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('dls_dlsblog')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('dls_dlsblog')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('dls_dlsblog')->__('Disabled'),
                    ),
                ),
            )
        );
        $form->addValues($this->getLayoutdesign()->getData());
        return parent::_prepareForm();
    }

    /**
     * get the current layout design
     *
     * @access public
     * @return DLS_DLSBlog_Model_Layoutdesign
     */
    public function getLayoutdesign()
    {
        return Mage::registry('layoutdesign');
    }
}
