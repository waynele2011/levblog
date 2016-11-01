<?php

class DLS_Blog_Model_Tag extends Mage_Core_Model_Abstract {

    const ENTITY = 'dls_blog_tag';
    const CACHE_TAG = 'dls_blog_tag';

    protected $_eventPrefix = 'dls_blog_tag';
    protected $_eventObject = 'tag';
    protected $_postInstance = null;

    public function _construct() {
        parent::_construct();
        $this->_init('dls_blog/tag');
    }

    protected function _beforeSave() {
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }

    protected function _afterSave() {
        $this->getPostInstance()->saveTagRelation($this);
        return parent::_afterSave();
    }

    public function getPostInstance() {
        if (!$this->_postInstance) {
            $this->_postInstance = Mage::getSingleton('dls_blog/tag_post');
        }
        return $this->_postInstance;
    }

    public function getSelectedPosts() {
        if (!$this->hasSelectedPosts()) {
            $posts = array();
            foreach ($this->getSelectedPostsCollection() as $post) {
                $posts[] = $post;
            }
            $this->setSelectedPosts($posts);
        }
        return $this->getData('selected_posts');
    }

    public function getSelectedPostsCollection() {
        $collection = $this->getPostInstance()->getPostsCollection($this);
        return $collection;
    }

    public function getParentBlogset() {
        if (!$this->hasData('_parent_blogset')) {
            if (!$this->getBlogsetId()) {
                return null;
            } else {
                $blogset = Mage::getModel('dls_blog/blogset')
                        ->load($this->getBlogsetId());
                if ($blogset->getId()) {
                    $this->setData('_parent_blogset', $blogset);
                } else {
                    $this->setData('_parent_blogset', null);
                }
            }
        }
        return $this->getData('_parent_blogset');
    }

    public function getDefaultValues() {
        $values = array();
        $values['status'] = 1;
        return $values;
    }

}
