<?php

/**
 * Frontend observer
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Model_Observer
{
    /**
     * add items to main menu
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return array()
     * @author Ultimate Module Creator
     */
    public function addItemsToTopmenuItems($observer)
    {
        $menu = $observer->getMenu();
        $tree = $menu->getTree();
        $action = Mage::app()->getFrontController()->getAction()->getFullActionName();
        $postNodeId = 'post';
        $data = array(
            'name' => Mage::helper('dls_dlsblog')->__('Posts'),
            'id' => $postNodeId,
            'url' => Mage::helper('dls_dlsblog/post')->getPostsUrl(),
            'is_active' => ($action == 'dls_dlsblog_post_index' || $action == 'dls_dlsblog_post_view')
        );
        $postNode = new Varien_Data_Tree_Node($data, 'id', $tree, $menu);
        $menu->addChild($postNode);
        return $this;
    }
}
