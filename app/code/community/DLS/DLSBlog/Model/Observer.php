<?php

class DLS_DLSBlog_Model_Observer {

    public function controllerActionLayoutLoadBefore(Varien_Event_Observer $observer) {
        /** @var $layout Mage_Core_Model_Layout */
        $name = Mage::app()->getRequest()->getRouteName() . "_" . Mage::app()->getRequest()->getControllerName() . "_" . Mage::app()->getRequest()->getActionName();
        $layout = $observer->getEvent()->getLayout();
        $id = Mage::app()->getRequest()->getParam('id', 0);
        $layoutDesign = '';
        if ($name == 'dls_dlsblog_blogset_view') {
            if ($curent_blogset = Mage::registry('current_blogset')) {
                $id = $curent_blogset->getId();
            }
            $blogset = Mage::getModel('dls_dlsblog/blogset')
                    ->load($id);
            $layoutDesign = $blogset->getParentLayoutdesign()->getBasicLayout();
        }
        if ($name == 'dls_dlsblog_filter_view') {
            if ($curent_filter = Mage::registry('current_filter')) {
                $id = $curent_filter->getId();
            }
            $filter = Mage::getModel('dls_dlsblog/filter')
                    ->setStoreId(Mage::app()->getStore()->getId())
                    ->load(1);
            $layoutDesign = $filter->getParentLayoutdesign()->getBasicLayout();
        }
        if ($name == 'dls_dlsblog_post_view') {
            if ($curent_post = Mage::registry('current_post')) {
                $id = $curent_post->getId();
            }
            $post = Mage::getModel('dls_dlsblog/post')
                    ->load($id);
            $layoutDesign = $post->getParentLayoutdesign()->getBasicLayout();
        }
        if ($name == 'dls_dlsblog_taxonomy_view') {
            if ($curent_taxonomy = Mage::registry('current_taxonomy')) {
                $id = $curent_taxonomy->getId();
            }
            $taxonomy = Mage::getModel('dls_dlsblog/taxonomy')->load($id);
            $collections = $taxonomy->getSelectedBlogsetsCollection();
            foreach($collections as $blogset){
                $layoutDesign = $blogset->getParentLayoutDesign()->getBasicLayout();
            }
        }
        switch ($layoutDesign) {
            case 1:
                $layoutDesign = 'page_one_column';
                break;
            case 2:
                $layoutDesign = 'page_two_columns_left';
                break;
            case 3:
                $layoutDesign = 'page_two_columns_right';
                break;
            case 4:
                $layoutDesign = 'page_three_columns';
                break;
            default:
                $layoutDesign = 'page_one_column';
                break;
        }
        $layout->getUpdate()->addHandle($layoutDesign);
    }

}
