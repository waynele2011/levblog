<?php

class DLS_Blog_Block_Adminhtml_Post_Edit_Tab_Taxonomy extends DLS_Blog_Block_Adminhtml_Taxonomy_Tree {

    protected $_taxonomyIds = null;
    protected $_selectedNodes = null;

    public function __construct() {
        parent::__construct();
        $this->setTemplate('dls_blog/post/edit/tab/taxonomy.phtml');
    }

    public function getPost() {
        return Mage::registry('current_post');
    }

    public function getTaxonomyIds() {
        if (is_null($this->_taxonomyIds)) {
            $taxonomies = $this->getPost()->getSelectedTaxonomies();
            $ids = array();
            foreach ($taxonomies as $taxonomy) {
                $ids[] = $taxonomy->getId();
            }
            $this->_taxonomyIds = $ids;
        }
        return $this->_taxonomyIds;
    }

    public function getIdsString() {
        return implode(',', $this->getTaxonomyIds());
    }

    public function getRootNode() {
        $root = $this->getRoot();
        if ($root && in_array($root->getId(), $this->getTaxonomyIds())) {
            $root->setChecked(true);
        }
        return $root;
    }

    public function getRoot($parentNodeTaxonomy = null, $recursionLevel = 3) {
        if (!is_null($parentNodeTaxonomy) && $parentNodeTaxonomy->getId()) {
            return $this->getNode($parentNodeTaxonomy, $recursionLevel);
        }
        $root = Mage::registry('taxonomy_root');
        if (is_null($root)) {
            $rootId = Mage::helper('dls_blog/taxonomy')->getRootTaxonomyId();
            $ids = $this->getSelectedTaxonomyPathIds($rootId);
            $tree = Mage::getResourceSingleton('dls_blog/taxonomy_tree')
                    ->loadByIds($ids, false, false);
            if ($this->getTaxonomy()) {
                $tree->loadEnsuredNodes($this->getTaxonomy(), $tree->getNodeById($rootId));
            }
            $tree->addCollectionData($this->getTaxonomyCollection());
            $root = $tree->getNodeById($rootId);
            Mage::register('taxonomy_root', $root);
        }
        return $root;
    }

    protected function _getNodeJson($node, $level = 1) {
        $item = parent::_getNodeJson($node, $level);
        if ($this->_isParentSelectedTaxonomy($node)) {
            $item['expanded'] = true;
        }
        if (in_array($node->getId(), $this->getTaxonomyIds())) {
            $item['checked'] = true;
        }
        return $item;
    }

    protected function _isParentSelectedTaxonomy($node) {
        $result = false;
        // Contains string with all taxonomy IDs of children (not exactly direct) of the node
        $allChildren = $node->getAllChildren();
        if ($allChildren) {
            $selectedTaxonomyIds = $this->getTaxonomyIds();
            $allChildrenArr = explode(',', $allChildren);
            for ($i = 0, $cnt = count($selectedTaxonomyIds); $i < $cnt; $i++) {
                $isSelf = $node->getId() == $selectedTaxonomyIds[$i];
                if (!$isSelf && in_array($selectedTaxonomyIds[$i], $allChildrenArr)) {
                    $result = true;
                    break;
                }
            }
        }
        return $result;
    }

    protected function _getSelectedNodes() {
        if ($this->_selectedNodes === null) {
            $this->_selectedNodes = array();
            $root = $this->getRoot();
            foreach ($this->getTaxonomyIds() as $taxonomyId) {
                if ($root) {
                    $this->_selectedNodes[] = $root->getTree()->getNodeById($taxonomyId);
                }
            }
        }
        return $this->_selectedNodes;
    }

    public function getTaxonomyChildrenJson($taxonomyId) {
        $taxonomy = Mage::getModel('dls_blog/taxonomy')->load($taxonomyId);
        $node = $this->getRoot($taxonomy, 1)->getTree()->getNodeById($taxonomyId);
        if (!$node || !$node->hasChildren()) {
            return '[]';
        }
        $children = array();
        foreach ($node->getChildren() as $child) {
            $children[] = $this->_getNodeJson($child);
        }
        return Mage::helper('core')->jsonEncode($children);
    }

    public function getLoadTreeUrl($expanded = null) {
        return $this->getUrl('*/*/taxonomiesJson', array('_current' => true));
    }

    public function getSelectedTaxonomyPathIds($rootId = false) {
        $ids = array();
        $taxonomyIds = $this->getTaxonomyIds();
        if (empty($taxonomyIds)) {
            return array();
        }
        $collection = Mage::getResourceModel('dls_blog/taxonomy_collection');
        if ($rootId) {
            $collection->addFieldToFilter('parent_id', $rootId);
        } else {
            $collection->addFieldToFilter('entity_id', array('in' => $taxonomyIds));
        }

        foreach ($collection as $item) {
            if ($rootId && !in_array($rootId, $item->getPathIds())) {
                continue;
            }
            foreach ($item->getPathIds() as $id) {
                if (!in_array($id, $ids)) {
                    $ids[] = $id;
                }
            }
        }
        return $ids;
    }

    public function buildNodeName($node) {
        $result = parent::buildNodeName($node);
        $result .= '<a target="_blank" href="' .
                $this->getUrl(
                        'adminhtml/blog_taxonomy/index', array(
                    'id' => $node->getId(),
                    'clear' => 1
                        )
                ) .
                '"><em>' . $this->__(' - Edit') . '</em></a>';
        return $result;
    }

}
