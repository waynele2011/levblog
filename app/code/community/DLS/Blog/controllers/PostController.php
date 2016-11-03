<?php

class DLS_Blog_PostController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if (Mage::helper('dls_blog/post')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                        'home', array(
                    'label' => Mage::helper('dls_blog')->__('Home'),
                    'link' => Mage::getUrl(),
                        )
                );
                $breadcrumbBlock->addCrumb(
                        'posts', array(
                    'label' => Mage::helper('dls_blog')->__('Posts'),
                    'link' => '',
                        )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', Mage::helper('dls_blog/post')->getPostsUrl());
        }
        if ($headBlock) {
            $headBlock->setTitle(Mage::getStoreConfig('dls_blog/post/meta_title'));
            $headBlock->setKeywords(Mage::getStoreConfig('dls_blog/post/meta_keywords'));
            $headBlock->setDescription(Mage::getStoreConfig('dls_blog/post/meta_description'));
        }
        $this->renderLayout();
    }

    protected function _initPost() {
        $postId = $this->getRequest()->getParam('id', 0);
        $post = Mage::getModel('dls_blog/post')
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
            $root->addBodyClass('blog-post blog-post' . $post->getId());
            $root->addBodyClass('blog');
        }
        if (Mage::helper('dls_blog/post')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                        'home', array(
                    'label' => Mage::helper('dls_blog')->__('Home'),
                    'link' => Mage::getUrl(),
                        )
                );
                $breadcrumbBlock->addCrumb(
                        'posts', array(
                    'label' => Mage::helper('dls_blog')->__('Posts'),
                    'link' => Mage::helper('dls_blog/post')->getPostsUrl(),
                        )
                );
                $breadcrumbBlock->addCrumb(
                        'post', array(
                    'label' => $post->getTitle(),
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
                $headBlock->setTitle($post->getTitle());
            }
            $headBlock->setKeywords($post->getMetaKeywords());
            $headBlock->setDescription($post->getMetaDescription());
        }
        $this->renderLayout();
    }

    public function commentpostAction() {
        $data = $this->getRequest()->getPost();
        $helper = Mage::helper('dls_blog');
        $post = $this->_initPost();
        $session = Mage::getSingleton('core/session');
        if ($post) {
            if ($post->getAllowComments()) {
                if ((Mage::getSingleton('customer/session')->isLoggedIn() ||
                        Mage::getStoreConfigFlag('dls_blog/post/allow_guest_comment'))) {
                    $comment = Mage::getModel('dls_blog/post_comment')->setData($data);
                    $validate = $comment->validate();
                    if ($validate === true) {
                        try {
                            $comment->setPostId($post->getId())
                                    ->setStatus(DLS_Blog_Model_Post_Comment::STATUS_PENDING)
                                    ->setCustomerId(Mage::getSingleton('customer/session')->getCustomerId())
                                    ->setStores(array(Mage::app()->getStore()->getId()))
                                    ->setRemoteIp(Mage::helper('core/http')->getRemoteAddr())
                                    ->save();
                            if(!$helper->canSendBySchedule()){
                                $helper->sendConfirmedCommentEmail($comment);
                            }
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
