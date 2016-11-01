<?php

class DLS_Blog_Block_Adminhtml_Filter_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId('filter_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('dls_blog')->__('Filter'));
    }

    protected function _beforeToHtml() {
        $this->addTab(
                'form_filter', array(
            'label' => Mage::helper('dls_blog')->__('Filter'),
            'title' => Mage::helper('dls_blog')->__('Filter'),
            'content' => $this->getLayout()->createBlock(
                            'dls_blog/adminhtml_filter_edit_tab_form'
                    )
                    ->toHtml(),
                )
        );

        $this->addTab(
                'form_meta_filter', array(
            'label' => Mage::helper('dls_blog')->__('Meta'),
            'title' => Mage::helper('dls_blog')->__('Meta'),
            'content' => $this->getLayout()->createBlock(
                            'dls_blog/adminhtml_filter_edit_tab_meta'
                    )
                    ->toHtml(),
                )
        );

        $this->addTab(
                'form_condition_filter', array(
            'label' => Mage::helper('dls_blog')->__('Filter Conditions'),
            'title' => Mage::helper('dls_blog')->__('Filter Conditions'),
            'content' => $this->getLayout()->createBlock(
                    'dls_blog/adminhtml_filter_edit_tab_condition'
            )->toHtml(),
                )
        );

        $this->addTab(
                'taxonomies', array(
            'label' => Mage::helper('dls_blog')->__('Taxonomies'),
            'url' => $this->getUrl('*/*/taxonomies', array('_current' => true)),
            'class' => 'ajax'
                )
        );
        return parent::_beforeToHtml();
    }

    public function getFilter() {
        return Mage::registry('current_filter');
    }

}
