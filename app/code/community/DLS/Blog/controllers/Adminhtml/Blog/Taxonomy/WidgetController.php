<?php

class DLS_Blog_Adminhtml_Blog_Taxonomy_WidgetController extends Mage_Adminhtml_Controller_Action {

    public function chooserAction() {
        $uniqId = $this->getRequest()->getParam('uniq_id');
        $grid = $this->getLayout()->createBlock(
                'dls_blog/adminhtml_taxonomy_widget_chooser', '', array(
            'id' => $uniqId,
                )
        );
        $this->getResponse()->setBody($grid->toHtml());
    }

    public function taxonomiesJsonAction() {
        if ($taxonomyId = (int) $this->getRequest()->getPost('id')) {
            $taxonomy = Mage::getModel('dls_blog/taxonomy')->load($taxonomyId);
            if ($taxonomy->getId()) {
                Mage::register('taxonomy', $taxonomy);
                Mage::register('current_taxonomy', $taxonomy);
            }
            $this->getResponse()->setBody(
                    $this->_getTaxonomyTreeBlock()->getTreeJson($taxonomy)
            );
        }
    }

    protected function _getTaxonomyTreeBlock() {
        return $this->getLayout()->createBlock(
                        'dls_blog/adminhtml_taxonomy_widget_chooser', '', array(
                    'id' => $this->getRequest()->getParam('uniq_id'),
                    'use_massaction' => $this->getRequest()->getParam('use_massaction', false)
                        )
        );
    }

}
