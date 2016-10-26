<?php

class DLS_DLSBlog_Block_Post_Widget_View extends Mage_Core_Block_Template implements
Mage_Widget_Block_Interface {

    protected $_htmlTemplate = 'dls_dlsblog/post/widget/view.phtml';

    protected function _beforeToHtml() {
        parent::_beforeToHtml();
        $postId = $this->getData('post_id');
        if ($postId) {
            $post = Mage::getModel('dls_dlsblog/post')
                    ->setStoreId(Mage::app()->getStore()->getId())
                    ->load($postId);
            if ($post->getStatus()) {
                $this->setCurrentPost($post);
                $this->setTemplate($this->_htmlTemplate);
            }
        }
        return $this;
    }

}
