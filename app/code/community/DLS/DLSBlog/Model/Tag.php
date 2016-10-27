<?php

/**
 * Tag model
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Model_Tag extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'dls_dlsblog_tag';
    const CACHE_TAG = 'dls_dlsblog_tag';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'dls_dlsblog_tag';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'tag';
    protected $_postInstance = null;

    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('dls_dlsblog/tag');
    }

    /**
     * before save tag
     *
     * @access protected
     * @return DLS_DLSBlog_Model_Tag
     * @author Ultimate Module Creator
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }

    /**
     * save tag relation
     *
     * @access public
     * @return DLS_DLSBlog_Model_Tag
     * @author Ultimate Module Creator
     */
    protected function _afterSave()
    {
        $this->getPostInstance()->saveTagRelation($this);
        return parent::_afterSave();
    }

    /**
     * get post relation model
     *
     * @access public
     * @return DLS_DLSBlog_Model_Tag_Post
     * @author Ultimate Module Creator
     */
    public function getPostInstance()
    {
        if (!$this->_postInstance) {
            $this->_postInstance = Mage::getSingleton('dls_dlsblog/tag_post');
        }
        return $this->_postInstance;
    }

    /**
     * get selected  array
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getSelectedPosts()
    {
        if (!$this->hasSelectedPosts()) {
            $posts = array();
            foreach ($this->getSelectedPostsCollection() as $post) {
                $posts[] = $post;
            }
            $this->setSelectedPosts($posts);
        }
        return $this->getData('selected_posts');
    }

    /**
     * Retrieve collection selected 
     *
     * @access public
     * @return DLS_DLSBlog_Model_Tag_Post_Collection
     * @author Ultimate Module Creator
     */
    public function getSelectedPostsCollection()
    {
        $collection = $this->getPostInstance()->getPostsCollection($this);
        return $collection;
    }

    /**
     * Retrieve parent 
     *
     * @access public
     * @return null|DLS_DLSBlog_Model_Blogset
     * @author Ultimate Module Creator
     */
    public function getParentBlogset()
    {
        if (!$this->hasData('_parent_blogset')) {
            if (!$this->getBlogsetId()) {
                return null;
            } else {
                $blogset = Mage::getModel('dls_dlsblog/blogset')
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

    /**
     * get default values
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getDefaultValues()
    {
        $values = array();
        $values['status'] = 1;
        return $values;
    }
    
}
