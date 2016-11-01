<?php

/**
 * Post comment admin edit tabs
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Adminhtml_Post_Comment_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Initialize Tabs
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('post_comment_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('dls_dlsblog')->__('Post Comment'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return DLS_DLSBlog_Block_Adminhtml_Post_Edit_Tabs
     * @author Ultimate Module Creator
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_post_comment',
            array(
                'label'   => Mage::helper('dls_dlsblog')->__('Post Comment'),
                'title'   => Mage::helper('dls_dlsblog')->__('Post Comment'),
                'content' => $this->getLayout()->createBlock(
                    'dls_dlsblog/adminhtml_post_comment_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addTab(
                'form_store_post_comment',
                array(
                    'label'   => Mage::helper('dls_dlsblog')->__('Store Views'),
                    'title'   => Mage::helper('dls_dlsblog')->__('Store Views'),
                    'content' => $this->getLayout()->createBlock(
                        'dls_dlsblog/adminhtml_post_comment_edit_tab_stores'
                    )
                    ->toHtml(),
                )
            );
        }
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve comment
     *
     * @access public
     * @return DLS_DLSBlog_Model_Post_Comment
     * @author Ultimate Module Creator
     */
    public function getComment()
    {
        return Mage::registry('current_comment');
    }
}
