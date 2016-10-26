<?php

class DLS_DLSBlog_Block_Post_List extends Mage_Core_Block_Template {

    public function _construct() {
        parent::_construct();
        $posts = Mage::getResourceModel('dls_dlsblog/post_collection')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('status', 1);
        $posts->setOrder('title', 'asc');
        $this->setPosts($posts);
    }

    protected function _prepareLayout() {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock(
                        'page/html_pager', 'dls_dlsblog.post.html.pager'
                )
                ->setCollection($this->getPosts());
        $this->setChild('pager', $pager);
        $this->getPosts()->load();
        return $this;
    }

    public function getPagerHtml() {
        return $this->getChildHtml('pager');
    }

}
