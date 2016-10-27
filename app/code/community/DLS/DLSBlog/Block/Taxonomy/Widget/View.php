<?php

/**
 * Taxonomy widget block
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Taxonomy_Widget_View extends Mage_Core_Block_Template implements
    Mage_Widget_Block_Interface
{
    protected $_htmlTemplate = 'dls_dlsblog/taxonomy/widget/view.phtml';

    /**
     * Prepare a for widget
     *
     * @access protected
     * @return DLS_DLSBlog_Block_Taxonomy_Widget_View
     * @author Ultimate Module Creator
     */
    protected function _beforeToHtml()
    {
        parent::_beforeToHtml();
        $taxonomyId = $this->getData('taxonomy_id');
        if ($taxonomyId) {
            $taxonomy = Mage::getModel('dls_dlsblog/taxonomy')
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
