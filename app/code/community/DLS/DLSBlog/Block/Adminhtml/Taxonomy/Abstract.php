<?php

class DLS_DLSBlog_Block_Adminhtml_Taxonomy_Abstract extends Mage_Adminhtml_Block_Template {

    public function getTaxonomy() {
        return Mage::registry('taxonomy');
    }

    public function getTaxonomyId() {
        if ($this->getTaxonomy()) {
            return $this->getTaxonomy()->getId();
        }
        return null;
    }

    public function getTaxonomyName() {
        return $this->getTaxonomy()->getName();
    }

    public function getTaxonomyPath() {
        if ($this->getTaxonomy()) {
            return $this->getTaxonomy()->getPath();
        }
        return Mage::helper('dls_dlsblog/taxonomy')->getRootTaxonomyId();
    }

    public function hasRootTaxonomy() {
        $root = $this->getRoot();
        if ($root && $root->getId()) {
            return true;
        }
        return false;
    }

    public function getRoot($parentNodeTaxonomy = null, $recursionLevel = 3) {
        if (!is_null($parentNodeTaxonomy) && $parentNodeTaxonomy->getId()) {
            return $this->getNode($parentNodeTaxonomy, $recursionLevel);
        }
        $root = Mage::registry('root');
        if (is_null($root)) {
            $rootId = Mage::helper('dls_dlsblog/taxonomy')->getRootTaxonomyId();
            $tree = Mage::getResourceSingleton('dls_dlsblog/taxonomy_tree')
                    ->load(null, $recursionLevel);
            if ($this->getTaxonomy()) {
                $tree->loadEnsuredNodes($this->getTaxonomy(), $tree->getNodeById($rootId));
            }
            $tree->addCollectionData($this->getTaxonomyCollection());
            $root = $tree->getNodeById($rootId);
            if ($root && $rootId != Mage::helper('dls_dlsblog/taxonomy')->getRootTaxonomyId()) {
                $root->setIsVisible(true);
            } elseif ($root && $root->getId() == Mage::helper('dls_dlsblog/taxonomy')->getRootTaxonomyId()) {
                $root->setName(Mage::helper('dls_dlsblog')->__('Root'));
            }
            Mage::register('root', $root);
        }
        return $root;
    }

    public function getRootByIds($ids) {
        $root = Mage::registry('root');
        if (null === $root) {
            $taxonomyTreeResource = Mage::getResourceSingleton('dls_dlsblog/taxonomy_tree');
            $ids = $taxonomyTreeResource->getExistingTaxonomyIdsBySpecifiedIds($ids);
            $tree = $taxonomyTreeResource->loadByIds($ids);
            $rootId = Mage::helper('dls_dlsblog/taxonomy')->getRootTaxonomyId();
            $root = $tree->getNodeById($rootId);
            if ($root && $rootId != Mage::helper('dls_dlsblog/taxonomy')->getRootTaxonomyId()) {
                $root->setIsVisible(true);
            } elseif ($root && $root->getId() == Mage::helper('dls_dlsblog/taxonomy')->getRootTaxonomyId()) {
                $root->setName(Mage::helper('dls_dlsblog')->__('Root'));
            }
            $tree->addCollectionData($this->getTaxonomyCollection());
            Mage::register('root', $root);
        }
        return $root;
    }

    public function getNode($parentNodeTaxonomy, $recursionLevel = 2) {
        $tree = Mage::getResourceModel('dls_dlsblog/taxonomy_tree');
        $nodeId = $parentNodeTaxonomy->getId();
        $parentId = $parentNodeTaxonomy->getParentId();
        $node = $tree->loadNode($nodeId);
        $node->loadChildren($recursionLevel);
        if ($node && $nodeId != Mage::helper('dls_dlsblog/taxonomy')->getRootTaxonomyId()) {
            $node->setIsVisible(true);
        } elseif ($node && $node->getId() == Mage::helper('dls_dlsblog/taxonomy')->getRootTaxonomyId()) {
            $node->setName(Mage::helper('dls_dlsblog')->__('Root'));
        }
        $tree->addCollectionData($this->getTaxonomyCollection());
        return $node;
    }

    public function getSaveUrl(array $args = array()) {
        $params = array('_current' => true);
        $params = array_merge($params, $args);
        return $this->getUrl('*/*/save', $params);
    }

    public function getEditUrl() {
        return $this->getUrl(
                        "*/dlsblog_taxonomy/edit", array('_current' => true, '_query' => false, 'id' => null, 'parent' => null)
        );
    }

    public function getRootIds() {
        return array(Mage::helper('dls_dlsblog/taxonomy')->getRootTaxonomyId());
    }

}
