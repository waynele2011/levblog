<?php

class DLS_DLSBlog_Block_Adminhtml_Taxonomy_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('taxonomy_');
        $form->setFieldNameSuffix('taxonomy');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
                'taxonomy_form', array('legend' => Mage::helper('dls_dlsblog')->__('Taxonomy'))
        );
        $fieldset->addType(
                'image', Mage::getConfig()->getBlockClassName('dls_dlsblog/adminhtml_taxonomy_helper_image')
        );
        if (!$this->getTaxonomy()->getId()) {
            $parentId = $this->getRequest()->getParam('parent');
            if (!$parentId) {
                $parentId = Mage::helper('dls_dlsblog/taxonomy')->getRootTaxonomyId();
            }
            $fieldset->addField(
                    'path', 'hidden', array(
                'name' => 'path',
                'value' => $parentId
                    )
            );
        } else {
            $fieldset->addField(
                    'id', 'hidden', array(
                'name' => 'id',
                'value' => $this->getTaxonomy()->getId()
                    )
            );
            $fieldset->addField(
                    'path', 'hidden', array(
                'name' => 'path',
                'value' => $this->getTaxonomy()->getPath()
                    )
            );
        }

        $fieldset->addField(
                'name', 'text', array(
            'label' => Mage::helper('dls_dlsblog')->__('Name'),
            'name' => 'name',
            'required' => true,
            'class' => 'required-entry',
                )
        );

        $fieldset->addField(
                'description', 'textarea', array(
            'label' => Mage::helper('dls_dlsblog')->__('Description'),
            'name' => 'description',
                )
        );

        $fieldset->addField(
                'small_image', 'image', array(
            'label' => Mage::helper('dls_dlsblog')->__('Small Image'),
            'name' => 'small_image',
                )
        );

        $fieldset->addField(
                'large_image', 'image', array(
            'label' => Mage::helper('dls_dlsblog')->__('Large image'),
            'name' => 'large_image',
                )
        );

        $fieldset->addField(
                'is_menu', 'select', array(
            'label' => Mage::helper('dls_dlsblog')->__('Display as menu item'),
            'name' => 'is_menu',
            'required' => true,
            'class' => 'required-entry',
            'values' => array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('dls_dlsblog')->__('Yes'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('dls_dlsblog')->__('No'),
                ),
            ),
                )
        );
        $fieldset->addField(
                'url_key', 'text', array(
            'label' => Mage::helper('dls_dlsblog')->__('Url key'),
            'name' => 'url_key',
            'note' => Mage::helper('dls_dlsblog')->__('Relative to Website Base URL')
                )
        );
        $fieldset->addField(
                'status', 'select', array(
            'label' => Mage::helper('dls_dlsblog')->__('Status'),
            'name' => 'status',
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
        $form->addValues($this->getTaxonomy()->getData());
        return parent::_prepareForm();
    }

    public function getTaxonomy() {
        return Mage::registry('taxonomy');
    }

}
