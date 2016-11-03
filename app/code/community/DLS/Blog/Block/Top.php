<?php

class DLS_Blog_Block_Top extends Mage_Core_Block_Template {

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
        $current_blogset = Mage::registry('current_blogset');
        if (!empty($current_blogset)) {
            $id = $current_blogset->getId();
            $blogset = Mage::getModel('dls_blog/blogset')
                    ->load($id);
            $layoutDesign = $blogset->getParentLayoutdesign();
        }
        $current_filter = Mage::registry('current_filter');
        if (!empty($current_filter)) {
            $id = $current_filter->getId();
            $filter = Mage::getModel('dls_blog/filter')
                    ->setStoreId(Mage::app()->getStore()->getId())
                    ->load(1);
            $layoutDesign = $filter->getParentLayoutdesign();
        }
        $current_post = Mage::registry('current_post');
        if (!empty($current_post)) {
            $id = $current_post->getId();
            $post = Mage::getModel('dls_blog/post')
                    ->load($id);
            $layoutDesign = $post->getParentLayoutdesign();
        }
        $current_taxonomy = Mage::registry('current_taxonomy');
        if (!empty($current_taxonomy)) {
            $id = $current_taxonomy->getId();
            $taxonomy = Mage::getModel('dls_blog/taxonomy')->load($id);
            $collections = $taxonomy->getSelectedBlogsetsCollection();
            foreach ($collections as $blogset) {
                $layoutDesign = $blogset->getParentLayoutDesign();
            }
        }
        return $layoutDesign->getDesignCode();
    }

}

?>