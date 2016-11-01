<?php

class DLS_Blog_Block_Blogset_View extends Mage_Core_Block_Template {

    public function getCurrentBlogset() {
        return Mage::registry('current_blogset');
    }

}
