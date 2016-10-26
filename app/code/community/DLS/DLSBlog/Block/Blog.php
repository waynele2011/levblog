<?php

class DLS_DLSBlog_Block_Blog extends Mage_Core_Block_Template {

    public function getPosts() {
        $collection = $this->_prepareCollection();

        //parent::_processCollection($collection);
        foreach ($collection as $_collection)
        {
            $data[] = $_collection->getData();
        }
        return $data;
    }

//    protected function _prepareLayout() {
//            parent::_prepareMetaData(self::$_helper);
//            return $breadcrumbs->addCrumb('blog', array('label' => self::$_helper->getTitle()));
//    }

    protected function _prepareCollection() {

        $collection = Mage::getModel('dls_dlsblog/post')->getCollection();
        $collection->addAttributeToSelect('*');
        return $collection;
    }

}
