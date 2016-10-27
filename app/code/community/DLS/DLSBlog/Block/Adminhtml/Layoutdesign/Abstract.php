<?php

/**
 * Layout design admin block abstract
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Adminhtml_Layoutdesign_Abstract extends Mage_Adminhtml_Block_Template
{
    /**
     * get current layout design
     *
     * @access public
     * @return DLS_DLSBlog_Model_Entity
     * @author Ultimate Module Creator
     */
    public function getLayoutdesign()
    {
        return Mage::registry('layoutdesign');
    }

    /**
     * get current layout design id
     *
     * @access public
     * @return int
     * @author Ultimate Module Creator
     */
    public function getLayoutdesignId()
    {
        if ($this->getLayoutdesign()) {
            return $this->getLayoutdesign()->getId();
        }
        return null;
    }

    /**
     * get current layout design Name
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getLayoutdesignName()
    {
        return $this->getLayoutdesign()->getName();
    }

    /**
     * get current layout design path
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getLayoutdesignPath()
    {
        if ($this->getLayoutdesign()) {
            return $this->getLayoutdesign()->getPath();
        }
        return Mage::helper('dls_dlsblog/layoutdesign')->getRootLayoutdesignId();
    }

    /**
     * check if there is a root layout design
     *
     * @access public
     * @return bool
     * @author Ultimate Module Creator
     */
    public function hasRootLayoutdesign()
    {
        $root = $this->getRoot();
        if ($root && $root->getId()) {
            return true;
        }
        return false;
    }

    /**
     * get the root
     *
     * @access public
     * @param DLS_DLSBlog_Model_Layoutdesign|null $parentNodeLayoutdesign
     * @param int $recursionLevel
     * @return Varien_Data_Tree_Node
     * @author Ultimate Module Creator
     */
    public function getRoot($parentNodeLayoutdesign = null, $recursionLevel = 3)
    {
        if (!is_null($parentNodeLayoutdesign) && $parentNodeLayoutdesign->getId()) {
            return $this->getNode($parentNodeLayoutdesign, $recursionLevel);
        }
        $root = Mage::registry('root');
        if (is_null($root)) {
            $rootId = Mage::helper('dls_dlsblog/layoutdesign')->getRootLayoutdesignId();
            $tree = Mage::getResourceSingleton('dls_dlsblog/layoutdesign_tree')
                ->load(null, $recursionLevel);
            if ($this->getLayoutdesign()) {
                $tree->loadEnsuredNodes($this->getLayoutdesign(), $tree->getNodeById($rootId));
            }
            $tree->addCollectionData($this->getLayoutdesignCollection());
            $root = $tree->getNodeById($rootId);
            if ($root && $rootId != Mage::helper('dls_dlsblog/layoutdesign')->getRootLayoutdesignId()) {
                $root->setIsVisible(true);
            } elseif ($root && $root->getId() == Mage::helper('dls_dlsblog/layoutdesign')->getRootLayoutdesignId()) {
                $root->setName(Mage::helper('dls_dlsblog')->__('Root'));
            }
            Mage::register('root', $root);
        }
        return $root;
    }

    /**
     * Get and register layout designs root by specified layout designs IDs
     *
     * @accsess public
     * @param array $ids
     * @return Varien_Data_Tree_Node
     * @author Ultimate Module Creator
     */
    public function getRootByIds($ids)
    {
        $root = Mage::registry('root');
        if (null === $root) {
            $layoutdesignTreeResource = Mage::getResourceSingleton('dls_dlsblog/layoutdesign_tree');
            $ids     = $layoutdesignTreeResource->getExistingLayoutdesignIdsBySpecifiedIds($ids);
            $tree   = $layoutdesignTreeResource->loadByIds($ids);
            $rootId = Mage::helper('dls_dlsblog/layoutdesign')->getRootLayoutdesignId();
            $root   = $tree->getNodeById($rootId);
            if ($root && $rootId != Mage::helper('dls_dlsblog/layoutdesign')->getRootLayoutdesignId()) {
                $root->setIsVisible(true);
            } elseif ($root && $root->getId() == Mage::helper('dls_dlsblog/layoutdesign')->getRootLayoutdesignId()) {
                $root->setName(Mage::helper('dls_dlsblog')->__('Root'));
            }
            $tree->addCollectionData($this->getLayoutdesignCollection());
            Mage::register('root', $root);
        }
        return $root;
    }

    /**
     * get specific node
     *
     * @access public
     * @param DLS_DLSBlog_Model_Layoutdesign $parentNodeLayoutdesign
     * @param $int $recursionLevel
     * @return Varien_Data_Tree_Node
     * @author Ultimate Module Creator
     */
    public function getNode($parentNodeLayoutdesign, $recursionLevel = 2)
    {
        $tree = Mage::getResourceModel('dls_dlsblog/layoutdesign_tree');
        $nodeId     = $parentNodeLayoutdesign->getId();
        $parentId   = $parentNodeLayoutdesign->getParentId();
        $node = $tree->loadNode($nodeId);
        $node->loadChildren($recursionLevel);
        if ($node && $nodeId != Mage::helper('dls_dlsblog/layoutdesign')->getRootLayoutdesignId()) {
            $node->setIsVisible(true);
        } elseif ($node && $node->getId() == Mage::helper('dls_dlsblog/layoutdesign')->getRootLayoutdesignId()) {
            $node->setName(Mage::helper('dls_dlsblog')->__('Root'));
        }
        $tree->addCollectionData($this->getLayoutdesignCollection());
        return $node;
    }

    /**
     * get url for saving data
     *
     * @access public
     * @param array $args
     * @return string
     * @author Ultimate Module Creator
     */
    public function getSaveUrl(array $args = array())
    {
        $params = array('_current'=>true);
        $params = array_merge($params, $args);
        return $this->getUrl('*/*/save', $params);
    }

    /**
     * get url for edit
     *
     * @access public
     * @param array $args
     * @return string
     * @author Ultimate Module Creator
     */
    public function getEditUrl()
    {
        return $this->getUrl(
            "*/dlsblog_layoutdesign/edit",
            array('_current' => true, '_query'=>false, 'id' => null, 'parent' => null)
        );
    }

    /**
     * Return root ids
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getRootIds()
    {
        return array(Mage::helper('dls_dlsblog/layoutdesign')->getRootLayoutdesignId());
    }
}
