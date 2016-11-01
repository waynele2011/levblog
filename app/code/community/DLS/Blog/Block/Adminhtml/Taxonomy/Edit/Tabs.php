<?php

class DLS_Blog_Block_Adminhtml_Taxonomy_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        $this->setId('taxonomy_info_tabs');
        $this->setDestElementId('taxonomy_tab_content');
        $this->setTitle(Mage::helper('dls_blog')->__('Category'));
        $this->setTemplate('widget/tabshoriz.phtml');
    }

    protected function _prepareLayout() {
        $this->addTab(
                'form_taxonomy', array(
            'label' => Mage::helper('dls_blog')->__('Category'),
            'title' => Mage::helper('dls_blog')->__('Category'),
            'content' => $this->getLayout()->createBlock(
                            'dls_blog/adminhtml_taxonomy_edit_tab_form'
                    )
                    ->toHtml(),
                )
        );
        $this->addTab(
                'form_meta_taxonomy', array(
            'label' => Mage::helper('dls_blog')->__('Meta'),
            'title' => Mage::helper('dls_blog')->__('Meta'),
            'content' => $this->getLayout()->createBlock(
                            'dls_blog/adminhtml_taxonomy_edit_tab_meta'
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
