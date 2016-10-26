<?php

class DLS_DLSBlog_Block_Post_Tag_List extends DLS_DLSBlog_Block_Tag_List {

    public function __construct() {
        parent::__construct();
        $post = $this->getPost();
        if ($post) {
            $this->getTags()->addPostFilter($post->getId());
            $this->getTags()->unshiftOrder('related_post.position', 'ASC');
        }
    }

    protected function _prepareLayout() {
        return $this;
    }

    public function getPost() {
        return Mage::registry('current_post');
    }

}
