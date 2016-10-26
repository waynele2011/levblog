<?php

class DLS_DLSBlog_Block_Filter_List extends Mage_Core_Block_Template {

    public function _construct() {
        parent::_construct();
        $filters = Mage::getResourceModel('dls_dlsblog/filter_collection')
                ->addFieldToFilter('status', 1);
        $filters->setOrder('name', 'asc');
        $this->setFilters($filters);
    }

    protected function _prepareLayout() {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock(
                        'page/html_pager', 'dls_dlsblog.filter.html.pager'
                )
                ->setCollection($this->getFilters());
        $this->setChild('pager', $pager);
        $this->getFilters()->load();
        return $this;
    }

    public function getPagerHtml() {
        return $this->getChildHtml('pager');
    }

}
