<?php

/**
 * Blog setting Posts list block
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Blogset_Post_List extends DLS_DLSBlog_Block_Post_List
{
    /**
     * initialize
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $blogset = $this->getBlogset();
        if ($blogset) {
            $collection = $this->getPosts()->addFieldToFilter('blogset_id', $blogset->getId());
            $this->setCollection($collection);
        }
    }

    /**
     * prepare the layout - actually do nothing
     *
     * @access protected
     * @return DLS_DLSBlog_Block_Blogset_Post_List
     * @author Ultimate Module Creator
     */
    protected function _prepareLayout()
    {
        $pager = $this->getLayout()->createBlock('page/html_pager', 'list.pager');
        $pager->setAvailableLimit(array(5 => 5, 10 => 10, 20 => 20, 'all' => 'all'));
        $pager->setCollection($this->getCollection());
        $this->setChild('pager', $pager);
        $this->getCollection()->load();
        return $this;
    }

    /**
     * get the current blog setting
     *
     * @access public
     * @return DLS_DLSBlog_Model_Blogset
     * @author Ultimate Module Creator
     */
    public function getBlogset()
    {
        return Mage::registry('current_blogset');
    }

    public function getPagerHtml() {
        return $this->getChildHtml('pager');
    }
}
