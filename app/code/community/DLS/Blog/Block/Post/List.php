<?php

class DLS_Blog_Block_Post_List extends Mage_Core_Block_Template {

    public function _construct() {
        parent::_construct();
        $attribute_code = 'publish_status';
        $postModel = Mage::getModel('dls_blog/post');
        $attribute = $postModel->getResource()->getAttribute($attribute_code);
        if ($attribute->usesSource()) {
            $publish_status_id = $attribute->getSource()->getOptionId(DLS_Blog_Model_Post::APPROVED_STATUS);
        }
        try {
            $date = date('Y-m-d H:i:s');
            $posts = Mage::getResourceModel('dls_blog/post_collection')
                    ->setStoreId(Mage::app()->getStore()->getId())
                    ->addAttributeToSelect('*')
                    ->addAttributeToFilter('status', 1)
                    ->addAttributeToFilter('publish_status', $publish_status_id)
                    ->addAttributeToFilter('publish_date', array('lteq' => $date));
            $posts->setOrder('title', 'asc');
            $this->setPosts($posts);
        } catch (Exception $exc) {
            Mage::log($exc->getMessage(), null, 'system.log');
        }
    }

    protected function _prepareLayout() {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock(
                        'page/html_pager', 'dls_blog.post.html.pager'
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
