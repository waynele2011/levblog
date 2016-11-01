<?php

class DLS_Blog_Block_Taxonomy_Widget_Subtree extends DLS_Blog_Block_Taxonomy_List implements
Mage_Widget_Block_Interface {

    protected $_template = 'dls_blog/taxonomy/widget/subtree.phtml';

    protected function _prepareLayout() {
        $this->getTaxonomies()->addFieldToFilter('entity_id', $this->getTaxonomyId());
        return $this;
    }

    protected function _getDisplayMode() {
        return 1;
    }

    public function getUniqueId() {
        if (!$this->getData('uniq_id')) {
            $this->setData('uniq_id', uniqid('subtree'));
        }
        return $this->getData('uniq_id');
    }

}
