<?php

class DLS_Blog_Model_Observer {

    public function addItemsToTopmenuItems($observer) {
        $module = Mage::app()->getRequest()->getModuleName();
        $menu = $observer->getMenu();
        $tree = $menu->getTree();
        $action = Mage::app()->getFrontController()->getAction()->getFullActionName();
        $postNodeId = 'post';
        $blogSettings = Mage::getModel('dls_blog/blogset')->getCollection()->addFieldToFilter('status', 1);
        foreach ($blogSettings as $blogset) {
            $data = array(
                'name' => $blogset->getName(),
                'id' => $blogset->getUrlKey(),
                'url' => Mage::getUrl($blogset->getUrlKey()),
                // 'is_active' => ($action == 'dls_blog_post_index' || $action == 'dls_blog_post_view')
                'is_active' => ($module == 'blog')
            );
            $postNode = new Varien_Data_Tree_Node($data, 'id', $tree, $menu);
            $menu->addChild($postNode);
        }
        return $this;
    }

    public function controllerActionLayoutLoadBefore(Varien_Event_Observer $observer) {

        $name = Mage::app()->getRequest()->getRouteName() . "_" . Mage::app()->getRequest()->getControllerName() . "_" . Mage::app()->getRequest()->getActionName();
        $layout = $observer->getEvent()->getLayout();
        $id = Mage::app()->getRequest()->getParam('id', 0);
        $layoutDesign = '';
        if ($name == 'dls_blog_blogset_view') {
            $current_blogset = Mage::registry('current_blogset');
            if (isset($current_blogset)) {
                $id = $current_blogset->getId();
            }
            $blogset = Mage::getModel('dls_blog/blogset')
                    ->load($id);
            $layoutDesign = $blogset->getParentLayoutdesign()->getBasicLayout();
        }
        if ($name == 'dls_blog_filter_view') {
            $current_filter = Mage::registry('current_filter');
            if (isset($current_filter)) {
                $id = $current_filter->getId();
            }
            $filter = Mage::getModel('dls_blog/filter')
                    ->setStoreId(Mage::app()->getStore()->getId())
                    ->load(1);
            $layoutDesign = $filter->getParentLayoutdesign()->getBasicLayout();
        }
        if ($name == 'dls_blog_post_view') {
            $current_post = Mage::registry('current_post');
            if (isset($current_post)) {
                $id = $current_post->getId();
            }
            $post = Mage::getModel('dls_blog/post')
                    ->load($id);
            $layoutDesign = $post->getParentLayoutdesign()->getBasicLayout();
        }
        if ($name == 'dls_blog_taxonomy_view') {
            $current_taxonomy = Mage::registry('current_taxonomy');
            if (issett($current_taxonomy)) {
                $id = $current_taxonomy->getId();
            }
            $taxonomy = Mage::getModel('dls_blog/taxonomy')->load($id);
            $collections = $taxonomy->getSelectedBlogsetsCollection();
            foreach ($collections as $blogset) {
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
