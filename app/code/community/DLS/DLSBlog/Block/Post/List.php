<?php

/**
 * Post list block
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Post_List extends Mage_Core_Block_Template
{
    /**
     * initialize
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function _construct()
    {
        parent::_construct();
        $attribute_code = 'publish_status';
        $postModel = Mage::getModel('dls_dlsblog/post');
        $attribute = $postModel->getResource()->getAttribute($attribute_code);
        if ($attribute->usesSource()) { 
            $publish_status_id = $attribute->getSource()->getOptionId(DLS_DLSBlog_Model_Post::APPROVED_STATUS);
        }
        try {
            $date = date('Y-m-d H:i:s');
            $posts = Mage::getResourceModel('dls_dlsblog/post_collection')
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

    /**
     * prepare the layout
     *
     * @access protected
     * @return DLS_DLSBlog_Block_Post_List
     * @author Ultimate Module Creator
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock(
            'page/html_pager',
            'dls_dlsblog.post.html.pager'
        )
        ->setCollection($this->getPosts());
        $this->setChild('pager', $pager);
        $this->getPosts()->load();
        return $this;
    }

    /**
     * get the pager html
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
}
