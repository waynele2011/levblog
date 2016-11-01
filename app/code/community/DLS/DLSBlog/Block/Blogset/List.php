<?php

/**
 * Blog list block
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Blogset_List extends Mage_Core_Block_Template
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
        $blogsets = Mage::getResourceModel('dls_dlsblog/blogset_collection')
                         ->addFieldToFilter('status', 1);
        $blogsets->setOrder('name', 'asc');
        $this->setBlogsets($blogsets);
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return DLS_DLSBlog_Block_Blogset_List
     * @author Ultimate Module Creator
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock(
            'page/html_pager',
            'dls_dlsblog.blogset.html.pager'
        )
        ->setCollection($this->getBlogsets());
        $this->setChild('pager', $pager);
        $this->getBlogsets()->load();
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
