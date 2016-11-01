<?php

class DLS_Blog_Adminhtml_Blog_Post_WidgetController extends Mage_Adminhtml_Controller_Action {

    public function chooserAction() {
        $uniqId = $this->getRequest()->getParam('uniq_id');
        $grid = $this->getLayout()->createBlock(
                'dls_blog/adminhtml_post_widget_chooser', '', array(
            'id' => $uniqId,
                )
        );
        $this->getResponse()->setBody($grid->toHtml());
    }

}
