<?php

class DLS_DLSBlog_Adminhtml_Dlsblog_Filter_WidgetController extends Mage_Adminhtml_Controller_Action {

    public function chooserAction() {
        $uniqId = $this->getRequest()->getParam('uniq_id');
        $grid = $this->getLayout()->createBlock(
                'dls_dlsblog/adminhtml_filter_widget_chooser', '', array(
            'id' => $uniqId,
                )
        );
        $this->getResponse()->setBody($grid->toHtml());
    }

}
