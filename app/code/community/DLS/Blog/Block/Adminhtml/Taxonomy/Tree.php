<?php

class DLS_Blog_Block_Adminhtml_Taxonomy_Tree extends DLS_Blog_Block_Adminhtml_Taxonomy_Abstract {

    public function __construct() {
        parent::__construct();
        $this->setTemplate('dls_blog/taxonomy/tree.phtml');
        $this->setUseAjax(true);
        $this->_withProductCount = true;
    }

    protected function _prepareLayout() {
        $addUrl = $this->getUrl(
                "*/*/add", array(
            '_current' => true,
            'id' => null,
            '_query' => false
                )
        );

        $this->setChild(
                'add_sub_button', $this->getLayout()->createBlock('adminhtml/widget_button')
                        ->setData(
                                array(
                                    'label' => Mage::helper('dls_blog')->__('Add Child Category'),
                                    'onclick' => "addNew('" . $addUrl . "', false)",
                                    'class' => 'add',
                                    'id' => 'add_child_taxonomy_button',
                                    'style' => $this->canAddChild() ? '' : 'display: none;'
                                )
                        )
        );

        $this->setChild(
                'add_root_button', $this->getLayout()->createBlock('adminhtml/widget_button')
                        ->setData(
                                array(
                                    'label' => Mage::helper('dls_blog')->__('Add Root Category'),
                                    'onclick' => "addNew('" . $addUrl . "', true)",
                                    'class' => 'add',
                                    'id' => 'add_root_taxonomy_button'
                                )
                        )
        );
        return parent::_prepareLayout();
    }

    public function getTaxonomyCollection() {
        $collection = $this->getData('taxonomy_collection');
        if (is_null($collection)) {
            $collection = Mage::getModel('dls_blog/taxonomy')->getCollection();
            $this->setData('taxonomy_collection', $collection);
        }
        return $collection;
    }

    public function getAddRootButtonHtml() {
        return $this->getChildHtml('add_root_button');
    }

    public function getAddSubButtonHtml() {
        return $this->getChildHtml('add_sub_button');
    }

    public function getExpandButtonHtml() {
        return $this->getChildHtml('expand_button');
    }

    public function getCollapseButtonHtml() {
        return $this->getChildHtml('collapse_button');
    }

    public function getLoadTreeUrl($expanded = null) {
        $params = array('_current' => true, 'id' => null, 'store' => null);
        if ((is_null($expanded) &&
                Mage::getSingleton('admin/session')->getTaxonomyIsTreeWasExpanded()) ||
                $expanded == true) {
            $params['expand_all'] = true;
        }
        return $this->getUrl('*/*/taxonomiesJson', $params);
    }

    public function getNodesUrl() {
        return $this->getUrl('*/blog_taxonomies/jsonTree');
    }

    public function getIsWasExpanded() {
        return Mage::getSingleton('admin/session')->getTaxonomyIsTreeWasExpanded();
    }

    public function getMoveUrl() {
        return $this->getUrl('*/blog_taxonomy/move');
    }

    public function getTree($parentNodeTaxonomy = null) {
        $rootArray = $this->_getNodeJson($this->getRoot($parentNodeTaxonomy));
        $tree = isset($rootArray['children']) ? $rootArray['children'] : array();
        return $tree;
    }

    public function getTreeJson($parentNodeTaxonomy = null) {
        $rootArray = $this->_getNodeJson($this->getRoot($parentNodeTaxonomy));
        $json = Mage::helper('core')->jsonEncode(isset($rootArray['children']) ? $rootArray['children'] : array());
        return $json;
    }

    public function getBreadcrumbsJavascript($path, $javascriptVarName) {
        if (empty($path)) {
            return '';
        }

        $taxonomies = Mage::getResourceSingleton('dls_blog/taxonomy_tree')
                ->loadBreadcrumbsArray($path);
        if (empty($taxonomies)) {
            return '';
        }
        foreach ($taxonomies as $key => $taxonomy) {
            $taxonomies[$key] = $this->_getNodeJson($taxonomy);
        }
        return
                '<script type="text/javascript">'
                . $javascriptVarName . ' = ' . Mage::helper('core')->jsonEncode($taxonomies) . ';'
                . ($this->canAddChild() ? '$("add_child_taxonomy_button").show();' : '$("add_child_taxonomy_button").hide();')
                . '</script>';
    }

    protected function _getNodeJson($node, $level = 0) {
        // create a node from data array
        if (is_array($node)) {
            $node = new Varien_Data_Tree_Node($node, 'entity_id', new Varien_Data_Tree);
        }
        $item = array();
        $item['text'] = $this->buildNodeName($node);
        $item['id'] = $node->getId();
        $item['path'] = $node->getData('path');
        $item['cls'] = 'folder';
        if ($node->getStatus()) {
            $item['cls'] .= ' active-category';
        } else {
            $item['cls'] .= ' no-active-category';
        }
        $item['allowDrop'] = true;
        $item['allowDrag'] = true;
        if ((int) $node->getChildrenCount() > 0) {
            $item['children'] = array();
        }
        $isParent = $this->_isParentSelectedTaxonomy($node);
        if ($node->hasChildren()) {
            $item['children'] = array();
            if (!($this->getUseAjax() && $node->getLevel() > 1 && !$isParent)) {
                foreach ($node->getChildren() as $child) {
                    $item['children'][] = $this->_getNodeJson($child, $level + 1);
                }
            }
        }
        if ($isParent || $node->getLevel() < 1) {
            $item['expanded'] = true;
        }
        return $item;
    }

    public function buildNodeName($node) {
        $result = $this->escapeHtml($node->getName());
        return $result;
    }

    protected function _isTaxonomyMoveable($node) {
        return true;
    }

    protected function _isParentSelectedTaxonomy($node) {
        if ($node && $this->getTaxonomy()) {
            $pathIds = $this->getTaxonomy()->getPathIds();
            if (in_array($node->getId(), $pathIds)) {
                return true;
            }
        }
        return false;
    }

    public function isClearEdit() {
        return (bool) $this->getRequest()->getParam('clear');
    }

    public function canAddRootTaxonomy() {
        return true;
    }

    public function canAddChild() {
        return true;
    }

}
