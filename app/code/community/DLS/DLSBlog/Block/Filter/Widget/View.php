<?php

class DLS_DLSBlog_Block_Filter_Widget_View extends Mage_Core_Block_Template implements
Mage_Widget_Block_Interface {

    protected $_htmlTemplate = 'dls_dlsblog/filter/widget/view.phtml';

    protected function _beforeToHtml() {
        parent::_beforeToHtml();
        $filterId = $this->getData('filter_id');
        if ($filterId) {
            $filter = Mage::getModel('dls_dlsblog/filter')
                    ->setStoreId(Mage::app()->getStore()->getId())
                    ->load($filterId);
            if ($filter->getStatus()) {
                $this->setCurrentFilter($filter);
                $this->setTemplate($this->_htmlTemplate);
            }
        }
        return $this;
    }

}
