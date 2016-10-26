<?php

class DLS_DLSBlog_Model_Post_Attribute_Backend_Image extends Mage_Eav_Model_Entity_Attribute_Backend_Abstract {

    public function afterSave($object) {
        $value = $object->getData($this->getAttribute()->getName());
        if (is_array($value) && !empty($value['delete'])) {
            $object->setData($this->getAttribute()->getName(), '');
            $this->getAttribute()->getEntity()
                    ->saveAttribute($object, $this->getAttribute()->getName());
            return;
        }

        $path = Mage::helper('dls_dlsblog/post_image')->getImageBaseDir();

        try {
            $uploader = new Varien_File_Uploader($this->getAttribute()->getName());
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            $result = $uploader->save($path);
            $object->setData($this->getAttribute()->getName(), $result['file']);
            $this->getAttribute()->getEntity()->saveAttribute($object, $this->getAttribute()->getName());
        } catch (Exception $e) {
            if ($e->getCode() != 666) {
                //throw $e;
            }
            return;
        }
    }

}
