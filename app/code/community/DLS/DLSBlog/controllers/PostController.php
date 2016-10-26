<?php

class DLS_DLSBlog_PostController extends Mage_Core_Controller_Front_Action {

    protected function _initPost() {
        $postId = $this->getRequest()->getParam('id', 0);
        $post = Mage::getModel('dls_dlsblog/post')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($postId);
        if (!$post->getId()) {
            return false;
        } elseif (!$post->getStatus()) {
            return false;
        }
        return $post;
    }

    public function viewAction() {
        $post = $this->_initPost();
        if (!$post) {
            $this->_forward('no-route');
            return;
        }
        Mage::register('current_post', $post);
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($root = $this->getLayout()->getBlock('root')) {
            $root->addBodyClass('dlsblog-post dlsblog-post' . $post->getId());
        }
        if (Mage::helper('dls_dlsblog/post')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                        'home', array(
                    'label' => Mage::helper('dls_dlsblog')->__('Home'),
                    'link' => Mage::getUrl(),
                        )
                );
                $breadcrumbBlock->addCrumb(
                        'post', array(
                    'label' => $post->getTitile(),
                    'link' => '',
                        )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', $post->getPostUrl());
        }
        if ($headBlock) {
            if ($post->getMetaTitle()) {
                $headBlock->setTitle($post->getMetaTitle());
            } else {
                $headBlock->setTitle($post->getTitile());
            }
            $headBlock->setKeywords($post->getMetaKeywords());
            $headBlock->setDescription($post->getMetaDescription());
        }
        $this->renderLayout();
    }

    public function commentpostAction() {
        $data = $this->getRequest()->getPost();
        $post = $this->_initPost();
        $session = Mage::getSingleton('core/session');
        if ($post) {
            if ($post->getAllowComments()) {
                if ((Mage::getSingleton('customer/session')->isLoggedIn() ||
                        Mage::getStoreConfigFlag('dls_dlsblog/post/allow_guest_comment'))) {
                    $comment = Mage::getModel('dls_dlsblog/post_comment')->setData($data);
                    $validate = $comment->validate();
                    if ($validate === true) {
                        try {
                            $comment->setPostId($post->getId())
                                    ->setStatus(DLS_DLSBlog_Model_Post_Comment::STATUS_PENDING)
                                    ->setCustomerId(Mage::getSingleton('customer/session')->getCustomerId())
                                    ->setStores(array(Mage::app()->getStore()->getId()))
                                    ->save();
                            $session->addSuccess($this->__('Your comment has been accepted for moderation.'));
                        } catch (Exception $e) {
                            $session->setPostCommentData($data);
                            $session->addError($this->__('Unable to post the comment.'));
                        }
                    } else {
                        $session->setPostCommentData($data);
                        if (is_array($validate)) {
                            foreach ($validate as $errorMessage) {
                                $session->addError($errorMessage);
                            }
                        } else {
                            $session->addError($this->__('Unable to post the comment.'));
                        }
                    }
                } else {
                    $session->addError($this->__('Guest comments are not allowed'));
                }
            } else {
                $session->addError($this->__('This post does not allow comments'));
            }
        }
        $this->_redirectReferer();
    }

}
