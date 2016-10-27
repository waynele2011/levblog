<?php

/**
 * blogset - taxonomy relation edit block
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Adminhtml_Blogset_Edit_Tab_Taxonomy extends DLS_DLSBlog_Block_Adminhtml_Taxonomy_Tree
{
    protected $_taxonomyIds = null;
    protected $_selectedNodes = null;

    /**
     * constructor
     * Specify template to use
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('dls_dlsblog/blogset/edit/tab/taxonomy.phtml');
    }

    /**
     * Retrieve currently edited blog setting
     *
     * @access public
     * @return DLS_DLSBlog_Model_Blogset
     * @author Ultimate Module Creator
     */
    public function getBlogset()
    {
        return Mage::registry('current_blogset');
    }

    /**
     * Return array with  IDs which the blog setting is linked to
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getTaxonomyIds()
    {
        if (is_null($this->_taxonomyIds)) {
            $taxonomies = $this->getBlogset()->getSelectedTaxonomies();
            $ids = array();
            foreach ($taxonomies as $taxonomy) {
                $ids[] = $taxonomy->getId();
            }
            $this->_taxonomyIds = $ids;
        }
        return $this->_taxonomyIds;
    }

    /**
     * Forms string out of getTaxonomyIds()
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getIdsString()
    {
        return implode(',', $this->getTaxonomyIds());
    }

    /**
     * Returns root node and sets 'checked' flag (if necessary)
     *
     * @access public
     * @return Varien_Data_Tree_Node
     * @author Ultimate Module Creator
     */
    public function getRootNode()
    {
        $root = $this->getRoot();
        if ($root && in_array($root->getId(), $this->getTaxonomyIds())) {
            $root->setChecked(true);
        }
        return $root;
    }

    /**
     * Returns root node
     *
     * @param DLS_DLSBlog_Model_Taxonomy|null $parentNodeTaxonomy
     * @param int  $recursionLevel
     * @return Varien_Data_Tree_Node
     * @author Ultimate Module Creator
     */
    public function getRoot($parentNodeTaxonomy = null, $recursionLevel = 3)
    {
        if (!is_null($parentNodeTaxonomy) && $parentNodeTaxonomy->getId()) {
            return $this->getNode($parentNodeTaxonomy, $recursionLevel);
        }
        $root = Mage::registry('taxonomy_root');
        if (is_null($root)) {
            $rootId = Mage::helper('dls_dlsblog/taxonomy')->getRootTaxonomyId();
            $ids    = $this->getSelectedTaxonomyPathIds($rootId);
            $tree   = Mage::getResourceSingleton('dls_dlsblog/taxonomy_tree')
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

    /**
     * Returns array with configuration of current node
     *
     * @access public
     * @param Varien_Data_Tree_Node $node
     * @param int $level How deep is the node in the tree
     * @return array
     * @author Ultimate Module Creator
     */
    protected function _getNodeJson($node, $level = 1)
    {
        $item = parent::_getNodeJson($node, $level);
        if ($this->_isParentSelectedTaxonomy($node)) {
            $item['expanded'] = true;
        }
        if (in_array($node->getId(), $this->getTaxonomyIds())) {
            $item['checked'] = true;
        }
        return $item;
    }

    /**
     * Returns whether $node is a parent (not exactly direct) of a selected node
     *
     * @access public
     * @param Varien_Data_Tree_Node $node
     * @return bool
     * @author Ultimate Module Creator
     */
    protected function _isParentSelectedTaxonomy($node)
    {
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

    /**
     * Returns array with nodes those are selected (contain current blog setting)
     *
     * @access protected
     * @return array
     * @author Ultimate Module Creator
     */
    protected function _getSelectedNodes()
    {
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

    /**
     * Returns JSON-encoded array of  children
     *
     * @access public
     * @param int $taxonomyId
     * @return string
     * @author Ultimate Module Creator
     */
    public function getTaxonomyChildrenJson($taxonomyId)
    {
        $taxonomy = Mage::getModel('dls_dlsblog/taxonomy')->load($taxonomyId);
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

    /**
     * Returns URL for loading tree
     *
     * @access public
     * @param null $expanded
     * @return string
     * @author Ultimate Module Creator
     */
    public function getLoadTreeUrl($expanded = null)
    {
        return $this->getUrl('*/*/taxonomiesJson', array('_current' => true));
    }

    /**
     * Return distinct path ids of selected 
     *
     * @access public
     * @param mixed $rootId Root taxonomy Id for context
     * @return array
     * @author Ultimate Module Creator
     */
    public function getSelectedTaxonomyPathIds($rootId = false)
    {
        $ids = array();
        $taxonomyIds = $this->getTaxonomyIds();
        if (empty($taxonomyIds)) {
            return array();
        }
        $collection = Mage::getResourceModel('dls_dlsblog/taxonomy_collection');
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

    /**
     * Get node label
     *
     * @access public
     * @param Varien_Object $node
     * @return string
     * @author Ultimate Module Creator
     */
    public function buildNodeName($node)
    {
        $result = parent::buildNodeName($node);
        $result .= '<a target="_blank" href="'.
            $this->getUrl(
                'adminhtml/dlsblog_taxonomy/index',
                array(
                    'id'    => $node->getId(),
                    'clear' => 1
                )
            ).
            '"><em>'.$this->__(' - Edit').'</em></a>';
        return $result;
    }
}
