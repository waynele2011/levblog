<?php

class DLS_DLSBlog_Block_Adminhtml_Taxonomy_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        $this->setId('taxonomy_info_tabs');
        $this->setDestElementId('taxonomy_tab_content');
        $this->setTitle(Mage::helper('dls_dlsblog')->__('Taxonomy'));
        $this->setTemplate('widget/tabshoriz.phtml');
    }

    protected function _prepareLayout() {
        $this->addTab(
                'form_taxonomy', array(
            'label' => Mage::helper('dls_dlsblog')->__('Taxonomy'),
            'title' => Mage::helper('dls_dlsblog')->__('Taxonomy'),
            'content' => $this->getLayout()->createBlock(
                            'dls_dlsblog/adminhtml_taxonomy_edit_tab_form'
                    )
                    ->toHtml(),
                )
        );
        $this->addTab(
                'form_meta_taxonomy', array(
            'label' => Mage::helper('dls_dlsblog')->__('Meta'),
            'title' => Mage::helper('dls_dlsblog')->__('Meta'),
            'content' => $this->getLayout()->createBlock(
                            'dls_dlsblog/adminhtml_taxonomy_edit_tab_meta'
                    )
                    ->toHtml(),
                )
        );

        return parent::_beforeToHtml();
    }

    public function getTaxonomy() {
        return Mage::registry('current_taxonomy');
    }

}
