<?php

class DLS_DLSBlog_Helper_Post extends Mage_Core_Helper_Abstract {

    public function getUseBreadcrumbs() {
        return Mage::getStoreConfigFlag('dls_dlsblog/post/breadcrumbs');
    }

    public function getFileBaseDir() {
        return Mage::getBaseDir('media') . DS . 'post' . DS . 'file';
    }

    public function getFileBaseUrl() {
        return Mage::getBaseUrl('media') . 'post' . '/' . 'file';
    }

    public function getAttributeSourceModelByInputType($inputType) {
        $inputTypes = $this->getAttributeInputTypes();
        if (!empty($inputTypes[$inputType]['source_model'])) {
            return $inputTypes[$inputType]['source_model'];
        }
        return null;
    }

    public function getAttributeInputTypes($inputType = null) {
        $inputTypes = array(
            'multiselect' => array(
                'backend_model' => 'eav/entity_attribute_backend_array'
            ),
            'boolean' => array(
                'source_model' => 'eav/entity_attribute_source_boolean'
            ),
            'file' => array(
                'backend_model' => 'dls_dlsblog/post_attribute_backend_file'
            ),
            'image' => array(
                'backend_model' => 'dls_dlsblog/post_attribute_backend_image'
            ),
        );

        if (is_null($inputType)) {
            return $inputTypes;
        } else if (isset($inputTypes[$inputType])) {
            return $inputTypes[$inputType];
        }
        return array();
    }

    public function getAttributeBackendModelByInputType($inputType) {
        $inputTypes = $this->getAttributeInputTypes();
        if (!empty($inputTypes[$inputType]['backend_model'])) {
            return $inputTypes[$inputType]['backend_model'];
        }
        return null;
    }

    public function postAttribute($post, $attributeHtml, $attributeName) {
        $attribute = Mage::getSingleton('eav/config')->getAttribute(
                DLS_DLSBlog_Model_Post::ENTITY, $attributeName
        );
        if ($attribute && $attribute->getId() && !$attribute->getIsWysiwygEnabled()) {
            if ($attribute->getFrontendInput() == 'textarea') {
                $attributeHtml = nl2br($attributeHtml);
            }
        }
        if ($attribute->getIsWysiwygEnabled()) {
            $attributeHtml = $this->_getTemplateProcessor()->filter($attributeHtml);
        }
        return $attributeHtml;
    }

    protected function _getTemplateProcessor() {
        if (null === $this->_templateProcessor) {
            $this->_templateProcessor = Mage::helper('catalog')->getPageTemplateProcessor();
        }
        return $this->_templateProcessor;
    }

}
