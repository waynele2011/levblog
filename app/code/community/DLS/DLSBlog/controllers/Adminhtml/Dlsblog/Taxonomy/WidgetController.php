<?php

/**
 * Taxonomy admin widget controller
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Adminhtml_Dlsblog_Taxonomy_WidgetController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Chooser Source action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function chooserAction()
    {
        $uniqId = $this->getRequest()->getParam('uniq_id');
        $grid = $this->getLayout()->createBlock(
            'dls_dlsblog/adminhtml_taxonomy_widget_chooser',
            '',
            array(
                'id' => $uniqId,
            )
        );
        $this->getResponse()->setBody($grid->toHtml());
    }

    /**
     * taxonomies json action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function taxonomiesJsonAction()
    {
        if ($taxonomyId = (int) $this->getRequest()->getPost('id')) {
            $taxonomy = Mage::getModel('dls_dlsblog/taxonomy')->load($taxonomyId);
            if ($taxonomy->getId()) {
                Mage::register('taxonomy', $taxonomy);
                Mage::register('current_taxonomy', $taxonomy);
            }
            $this->getResponse()->setBody(
                $this->_getTaxonomyTreeBlock()->getTreeJson($taxonomy)
            );
        }
    }

    /**
     * get taxonomy tree block
     *
     * @access protected
     * @return DLS_DLSBlog_Block_Adminhtml_Taxonomy_Widget_Chooser
     * @author Ultimate Module Creator
     */
    protected function _getTaxonomyTreeBlock()
    {
        return $this->getLayout()->createBlock(
            'dls_dlsblog/adminhtml_taxonomy_widget_chooser',
            '',
            array(
                'id' => $this->getRequest()->getParam('uniq_id'),
                'use_massaction' => $this->getRequest()->getParam('use_massaction', false)
            )
        );
    }
}
