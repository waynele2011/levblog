<?php

/**
 * Admin search model
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Model_Adminhtml_Search_Post extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return DLS_DLSBlog_Model_Adminhtml_Search_Post
     * @author Ultimate Module Creator
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('dls_dlsblog/post_collection')
            ->addAttributeToFilter('title', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $post) {
            $arr[] = array(
                'id'          => 'post/1/'.$post->getId(),
                'type'        => Mage::helper('dls_dlsblog')->__('Post'),
                'name'        => $post->getTitle(),
                'description' => $post->getTitle(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/dlsblog_post/edit',
                    array('id'=>$post->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
