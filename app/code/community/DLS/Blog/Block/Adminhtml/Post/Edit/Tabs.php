<?php

class DLS_Blog_Block_Adminhtml_Post_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId('post_info_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('dls_blog')->__('Post Information'));
    }

    protected function _prepareLayout() {
        $post = $this->getPost();
        $entity = Mage::getModel('eav/entity_type')
                ->load('dls_blog_post', 'entity_type_code');
        $attributes = Mage::getResourceModel('eav/entity_attribute_collection')
                ->setEntityTypeFilter($entity->getEntityTypeId());
        $attributes->addFieldToFilter(
                'attribute_code', array(
            'nin' => array('meta_title', 'meta_description', 'meta_keywords')
                )
        );
        $attributes->getSelect()->order('additional_table.position', 'ASC');

        $this->addTab(
                'info', array(
            'label' => Mage::helper('dls_blog')->__('Post Information'),
            'content' => $this->getLayout()->createBlock(
                            'dls_blog/adminhtml_post_edit_tab_attributes'
                    )
                    ->setAttributes($attributes)
                    ->toHtml(),
                )
        );
        $seoAttributes = Mage::getResourceModel('eav/entity_attribute_collection')
                ->setEntityTypeFilter($entity->getEntityTypeId())
                ->addFieldToFilter(
                'attribute_code', array(
            'in' => array('meta_title', 'meta_description', 'meta_keywords')
                )
        );
        $seoAttributes->getSelect()->order('additional_table.position', 'ASC');

        $this->addTab(
                'meta', array(
            'label' => Mage::helper('dls_blog')->__('Meta'),
            'title' => Mage::helper('dls_blog')->__('Meta'),
            'content' => $this->getLayout()->createBlock(
                            'dls_blog/adminhtml_post_edit_tab_attributes'
                    )
                    ->setAttributes($seoAttributes)
                    ->toHtml(),
                )
        );
        $this->addTab(
                'taxonomies', array(
            'label' => Mage::helper('dls_blog')->__('Categories'),
            'url' => $this->getUrl('*/*/taxonomies', array('_current' => true)),
            'class' => 'ajax'
                )
        );
        $this->addTab(
                'tags', array(
            'label' => Mage::helper('dls_blog')->__('Tags'),
            'url' => $this->getUrl('*/*/tags', array('_current' => true)),
            'class' => 'ajax'
                )
        );
        $this->addTab(
                'products', array(
            'label' => Mage::helper('dls_blog')->__('Associated Products'),
            'url' => $this->getUrl('*/*/products', array('_current' => true)),
            'class' => 'ajax'
                )
        );
        return parent::_beforeToHtml();
    }

    public function getPost() {
        return Mage::registry('current_post');
    }

}
