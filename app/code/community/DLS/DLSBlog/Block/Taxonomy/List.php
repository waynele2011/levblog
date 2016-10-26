<?php

class DLS_DLSBlog_Block_Taxonomy_List extends Mage_Core_Block_Template {

    public function _construct() {
        parent::_construct();
        $taxonomies = Mage::getResourceModel('dls_dlsblog/taxonomy_collection')
                ->addFieldToFilter('status', 1);
        ;
        $taxonomies->getSelect()->order('main_table.position');
        $this->setTaxonomies($taxonomies);
    }

    protected function _prepareLayout() {
        parent::_prepareLayout();
        $this->getTaxonomies()->addFieldToFilter('level', 1);
        if ($this->_getDisplayMode() == 0) {
            $pager = $this->getLayout()->createBlock(
                            'page/html_pager', 'dls_dlsblog.taxonomies.html.pager'
                    )
                    ->setCollection($this->getTaxonomies());
            $this->setChild('pager', $pager);
            $this->getTaxonomies()->load();
        }
        return $this;
    }

    public function getPagerHtml() {
        return $this->getChildHtml('pager');
    }

    protected function _getDisplayMode() {
        return Mage::getStoreConfigFlag('dls_dlsblog/taxonomy/tree');
    }

    public function drawTaxonomy($taxonomy, $level = 0) {
        $html = '';
        $recursion = $this->getRecursion();
        if ($recursion !== '0' && $level >= $recursion) {
            return '';
        }
        $storeIds = Mage::getResourceSingleton(
                        'dls_dlsblog/taxonomy'
                )
                ->lookupStoreIds($taxonomy->getId());
        $validStoreIds = array(0, Mage::app()->getStore()->getId());
        if (!array_intersect($storeIds, $validStoreIds)) {
            return '';
        }
        if (!$taxonomy->getStatus()) {
            return '';
        }
        $children = $taxonomy->getChildrenTaxonomies();
        $activeChildren = array();
        if ($recursion == 0 || $level < $recursion - 1) {
            foreach ($children as $child) {
                $childStoreIds = Mage::getResourceSingleton(
                                'dls_dlsblog/taxonomy'
                        )
                        ->lookupStoreIds($child->getId());
                $validStoreIds = array(0, Mage::app()->getStore()->getId());
                if (!array_intersect($childStoreIds, $validStoreIds)) {
                    continue;
                }
                if ($child->getStatus()) {
                    $activeChildren[] = $child;
                }
            }
        }
        $html .= '<li>';
        $html .= '<a href="' . $taxonomy->getTaxonomyUrl() . '">' . $taxonomy->getName() . '</a>';
        if (count($activeChildren) > 0) {
            $html .= '<ul>';
            foreach ($children as $child) {
                $html .= $this->drawTaxonomy($child, $level + 1);
            }
            $html .= '</ul>';
        }
        $html .= '</li>';
        return $html;
    }

    public function getRecursion() {
        if (!$this->hasData('recursion')) {
            $this->setData('recursion', Mage::getStoreConfig('dls_dlsblog/taxonomy/recursion'));
        }
        return $this->getData('recursion');
    }

}
