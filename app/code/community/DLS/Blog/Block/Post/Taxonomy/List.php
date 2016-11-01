<?php

class DLS_Blog_Block_Post_Taxonomy_List extends DLS_Blog_Block_Taxonomy_List {

    public function __construct() {
        parent::__construct();
        $post = $this->getPost();
        if ($post) {
            $this->getTaxonomies()->addPostFilter($post->getId());
            $this->getTaxonomies()->unshiftOrder('related_post.position', 'ASC');
        }
    }

    protected function _prepareLayout() {
        return $this;
    }

    public function getPost() {
        return Mage::registry('current_post');
    }

}
