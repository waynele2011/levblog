<?php

class DLS_DLSBlog_Block_Tag_List extends Mage_Core_Block_Template {

    public function _construct() {
        parent::_construct();
        $tags = Mage::getResourceModel('dls_dlsblog/tag_collection')
                ->addFieldToFilter('status', 1);
        $tags->setOrder('name', 'asc');
        $this->setTags($tags);
    }

    protected function _prepareLayout() {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock(
                        'page/html_pager', 'dls_dlsblog.tag.html.pager'
                )
                ->setCollection($this->getTags());
        $this->setChild('pager', $pager);
        $this->getTags()->load();
        return $this;
    }

    public function getPagerHtml() {
        return $this->getChildHtml('pager');
    }

}
