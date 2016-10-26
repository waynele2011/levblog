<?php

class DLS_DLSBlog_Block_Blogset_View extends Mage_Core_Block_Template {

    public function getCurrentBlogset() {
        return Mage::registry('current_blogset');
    }

}
