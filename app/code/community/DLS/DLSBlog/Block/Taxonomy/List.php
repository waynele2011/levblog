<?php

/**
 * Category list block
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Taxonomy_List extends Mage_Core_Block_Template
{
    /**
     * initialize
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function _construct()
    {
        parent::_construct();
        $taxonomies = Mage::getResourceModel('dls_dlsblog/taxonomy_collection')
                         ->addFieldToFilter('status', 1);
        ;
        $taxonomies->getSelect()->order('main_table.position');
        $this->setTaxonomies($taxonomies);
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return DLS_DLSBlog_Block_Taxonomy_List
     * @author Ultimate Module Creator
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->getTaxonomies()->addFieldToFilter('level', 1);
        if ($this->_getDisplayMode() == 0) {
            $pager = $this->getLayout()->createBlock(
                'page/html_pager',
                'dls_dlsblog.taxonomies.html.pager'
            )
            ->setCollection($this->getTaxonomies());
            $this->setChild('pager', $pager);
            $this->getTaxonomies()->load();
        }
        return $this;
    }

    /**
     * get the pager html
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * get the display mode
     *
     * @access protected
     * @return int
     * @author Ultimate Module Creator
     */
    protected function _getDisplayMode()
    {
        return Mage::getStoreConfigFlag('dls_dlsblog/taxonomy/tree');
    }

    /**
     * draw taxonomy
     *
     * @access public
     * @param DLS_DLSBlog_Model_Taxonomy
     * @param int $level
     * @return int
     * @author Ultimate Module Creator
     */
    public function drawTaxonomy($taxonomy, $level = 0)
    {
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
        if ($recursion == 0 || $level < $recursion-1) {
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
        $html .= '<a href="'.$taxonomy->getTaxonomyUrl().'">'.$taxonomy->getName().'</a>';
        if (count($activeChildren) > 0) {
            $html .= '<ul>';
            foreach ($children as $child) {
                $html .= $this->drawTaxonomy($child, $level+1);
            }
            $html .= '</ul>';
        }
        $html .= '</li>';
        return $html;
    }

    /**
     * get recursion
     *
     * @access public
     * @return int
     * @author Ultimate Module Creator
     */
    public function getRecursion()
    {
        if (!$this->hasData('recursion')) {
            $this->setData('recursion', Mage::getStoreConfig('dls_dlsblog/taxonomy/recursion'));
        }
        return $this->getData('recursion');
    }
}
