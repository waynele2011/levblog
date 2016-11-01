<?php

class DLS_Blog_Block_Taxonomy_Widget_View extends Mage_Core_Block_Template implements
Mage_Widget_Block_Interface {

    protected $_htmlTemplate = 'dls_blog/taxonomy/widget/view.phtml';

    protected function _beforeToHtml() {
        parent::_beforeToHtml();
        $taxonomyId = $this->getData('taxonomy_id');
        if ($taxonomyId) {
            $taxonomy = Mage::getModel('dls_blog/taxonomy')
                    ->setStoreId(Mage::app()->getStore()->getId())
                    ->load($taxonomyId);
            if ($taxonomy->getStatusPath()) {
                $this->setCurrentTaxonomy($taxonomy);
                $this->setTemplate($this->_htmlTemplate);
            }
        }
        return $this;
    }

}
