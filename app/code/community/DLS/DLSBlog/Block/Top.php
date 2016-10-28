<?php

class DLS_DLSBlog_Block_Top extends Mage_Core_Block_Template {

    public function getChildsHtml() {
        $designcode = $this->getCurrentLayoutDesign();
        $designcodearr = Mage::helper('core')->jsonDecode($designcode);
        $html = '';
        if (isset($designcodearr['top'])) {
            foreach ($designcodearr['top'] as $identifier) {
                $html .= $this->getLayout()->createBlock('cms/block')->setBlockId($identifier)->toHtml();
            }
        }
        return $html;
    }

    protected function getCurrentLayoutDesign() {
        $id = Mage::app()->getRequest()->getParam('id', 0);
        $layoutDesign = '';

        if ($curent_blogset = Mage::registry('current_blogset')) {
            $id = $curent_blogset->getId();
            $blogset = Mage::getModel('dls_dlsblog/blogset')
                    ->load($id);
            $layoutDesign = $blogset->getParentLayoutdesign();
        }
        if ($curent_filter = Mage::registry('current_filter')) {
            $id = $curent_filter->getId();
            $filter = Mage::getModel('dls_dlsblog/filter')
                    ->setStoreId(Mage::app()->getStore()->getId())
                    ->load(1);
            $layoutDesign = $filter->getParentLayoutdesign();
        }
        if ($curent_post = Mage::registry('current_post')) {
            $id = $curent_post->getId();
            $post = Mage::getModel('dls_dlsblog/post')
                    ->load($id);
            $layoutDesign = $post->getParentLayoutdesign();
        }
        if ($curent_taxonomy = Mage::registry('current_taxonomy')) {
            $id = $curent_taxonomy->getId();
            $taxonomy = Mage::getModel('dls_dlsblog/taxonomy')->load($id);
            $collections = $taxonomy->getSelectedBlogsetsCollection();
            foreach($collections as $blogset){
                $layoutDesign = $blogset->getParentLayoutDesign();
            }
        }
        return $layoutDesign->getDesignCode();
    }

}

?>