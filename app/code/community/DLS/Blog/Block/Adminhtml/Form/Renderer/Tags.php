<?php

class DLS_Blog_Block_Adminhtml_Form_Renderer_Tags extends Mage_Core_Block_Template {

    protected function _construct() {
        parent::_construct();
    }

    public function getAvailableTagsArray() {
        $collection = Mage::getResourceModel('dls_blog/tag_collection')
                ->addFieldToFilter('status', 1);
        $collection->getSelect()->order('main_table.name');
        $availableTags = array();
        foreach ($collection as $tag) {
            $availableTags[] = $tag->getName();
        }
        return $availableTags;
    }

}
