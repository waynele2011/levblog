<?php

/**
 * Frontend observer
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Model_Observer {

    /**
     * add items to main menu
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return array()
     * @author Ultimate Module Creator
     */
    public function addItemsToTopmenuItems($observer) {
        $module = Mage::app()->getRequest()->getModuleName();
        $menu = $observer->getMenu();
        $tree = $menu->getTree();
        $action = Mage::app()->getFrontController()->getAction()->getFullActionName();
        $postNodeId = 'post';
        $blogSettings = Mage::getModel('dls_dlsblog/blogset')->getCollection()->addFieldToFilter('status', 1);
        foreach ($blogSettings as $blogset) {
            $data = array(
                'name' => $blogset->getName(),
                'id' => $blogset->getUrlKey(),
                'url' => Mage::getUrl($blogset->getUrlKey()),
                // 'is_active' => ($action == 'dls_dlsblog_post_index' || $action == 'dls_dlsblog_post_view')
                'is_active' => ($module == 'dlsblog')
            );
            $postNode = new Varien_Data_Tree_Node($data, 'id', $tree, $menu);
            $menu->addChild($postNode);
        }
        return $this;
    }

}
