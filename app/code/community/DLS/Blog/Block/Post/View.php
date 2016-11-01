<?php

class DLS_Blog_Block_Post_View extends Mage_Core_Block_Template {

    public function getCurrentPost() {
        return Mage::registry('current_post');
    }

}
