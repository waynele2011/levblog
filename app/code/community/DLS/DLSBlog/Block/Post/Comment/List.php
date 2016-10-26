<?php

class DLS_DLSBlog_Block_Post_Comment_List extends Mage_Core_Block_Template {

    public function __construct() {
        parent::__construct();
        $post = $this->getPost();
        $comments = Mage::getResourceModel('dls_dlsblog/post_comment_collection')
                ->addFieldToFilter('post_id', $post->getId())
                ->addStoreFilter(Mage::app()->getStore())
                ->addFieldToFilter('status', 1);
        $comments->setOrder('created_at', 'asc');
        $this->setComments($comments);
    }

    protected function _prepareLayout() {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock(
                        'page/html_pager', 'dls_dlsblog.post.html.pager'
                )
                ->setCollection($this->getComments());
        $this->setChild('pager', $pager);
        $this->getComments()->load();
        return $this;
    }

    public function getPagerHtml() {
        return $this->getChildHtml('pager');
    }

    public function getPost() {
        return Mage::registry('current_post');
    }

}
