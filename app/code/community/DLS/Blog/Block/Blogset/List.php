<?php

class DLS_Blog_Block_Blogset_List extends Mage_Core_Block_Template {

    public function _construct() {
        parent::_construct();
        $blogsets = Mage::getResourceModel('dls_blog/blogset_collection')
                ->addFieldToFilter('status', 1);
        $blogsets->setOrder('name', 'asc');
        $this->setBlogsets($blogsets);
    }

    protected function _prepareLayout() {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock(
                        'page/html_pager', 'dls_blog.blogset.html.pager'
                )
                ->setCollection($this->getBlogsets());
        $this->setChild('pager', $pager);
        $this->getBlogsets()->load();
        return $this;
    }

    public function getPagerHtml() {
        return $this->getChildHtml('pager');
    }

}
