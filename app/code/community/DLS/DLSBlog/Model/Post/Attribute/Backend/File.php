<?php

/**
 * Admin backend source model for files
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Model_Post_Attribute_Backend_File extends Mage_Eav_Model_Entity_Attribute_Backend_Abstract
{

    /**
     * Save uploaded file and set its name to post object
     *
     * @access public
     * @param Varien_Object $object
     * @return null
     * @author Ultimate Module Creator
     */
    public function afterSave($object)
    {
        $value = $object->getData($this->getAttribute()->getName());
        if (is_array($value) && !empty($value['delete'])) {
            $object->setData($this->getAttribute()->getName(), '');
            $this->getAttribute()->getEntity()
                ->saveAttribute($object, $this->getAttribute()->getName());
            return;
        }

        $path = Mage::helper('dls_dlsblog/post')->getFileBaseDir();

        try {
            $uploader = new Varien_File_Uploader($this->getAttribute()->getName());
            //set allowed file extensions if you need
            //$uploader->setAllowedExtensions(array('mp4', 'mov', 'f4v', 'flv'));
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
