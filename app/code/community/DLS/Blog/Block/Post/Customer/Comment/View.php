<?php

class DLS_Blog_Block_Post_Customer_Comment_View extends Mage_Customer_Block_Account_Dashboard {

    public function getComment() {
        return Mage::registry('current_comment');
    }

    public function getPost() {
        return Mage::registry('current_post');
    }

}
