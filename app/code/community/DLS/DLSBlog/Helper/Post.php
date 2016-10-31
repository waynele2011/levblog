<?php 

/**
 * Post helper
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Helper_Post extends Mage_Core_Helper_Abstract
{

    /**
     * get the url to the posts list page
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getPostsUrl()
    {
        if ($listKey = Mage::getStoreConfig('dls_dlsblog/post/url_rewrite_list')) {
            return Mage::getUrl('', array('_direct'=>$listKey));
        }
        return Mage::getUrl('dls_dlsblog/post/index');
    }

    /**
     * check if breadcrumbs can be used
     *
     * @access public
     * @return bool
     * @author Ultimate Module Creator
     */
    public function getUseBreadcrumbs()
    {
        return Mage::getStoreConfigFlag('dls_dlsblog/post/breadcrumbs');
    }

    /**
     * get base files dir
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getFileBaseDir()
    {
        return Mage::getBaseDir('media').DS.'post'.DS.'file';
    }

    /**
     * get base file url
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getFileBaseUrl()
    {
        return Mage::getBaseUrl('media').'post'.'/'.'file';
    }

    /**
     * get post attribute source model
     *
     * @access public
     * @param string $inputType
     * @return mixed (string|null)
     * @author Ultimate Module Creator
     */
     public function getAttributeSourceModelByInputType($inputType)
     {
         $inputTypes = $this->getAttributeInputTypes();
         if (!empty($inputTypes[$inputType]['source_model'])) {
             return $inputTypes[$inputType]['source_model'];
         }
         return null;
     }

    /**
     * get attribute input types
     *
     * @access public
     * @param string $inputType
     * @return array()
     * @author Ultimate Module Creator
     */
    public function getAttributeInputTypes($inputType = null)
    {
        $inputTypes = array(
            'multiselect' => array(
                'backend_model' => 'eav/entity_attribute_backend_array',
                'source_model' => 'eav/entity_attribute_source_table'
            ),
            'boolean'     => array(
                'source_model'  => 'eav/entity_attribute_source_boolean'
            ),
            'file'          => array(
                'backend_model' => 'dls_dlsblog/post_attribute_backend_file'
            ),
            'image'          => array(
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

    /**
     * get post attribute backend model
     *
     * @access public
     * @param string $inputType
     * @return mixed (string|null)
     * @author Ultimate Module Creator
     */
    public function getAttributeBackendModelByInputType($inputType)
    {
        $inputTypes = $this->getAttributeInputTypes();
        if (!empty($inputTypes[$inputType]['backend_model'])) {
            return $inputTypes[$inputType]['backend_model'];
        }
        return null;
    }

    /**
     * filter attribute content
     *
     * @access public
     * @param DLS_DLSBlog_Model_Post $post
     * @param string $attributeHtml
     * @param string @attributeName
     * @return string
     * @author Ultimate Module Creator
     */
    public function postAttribute($post, $attributeHtml, $attributeName)
    {
        $attribute = Mage::getSingleton('eav/config')->getAttribute(
            DLS_DLSBlog_Model_Post::ENTITY,
            $attributeName
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

    /**
     * get the template processor
     *
     * @access protected
     * @return Mage_Catalog_Model_Template_Filter
     * @author Ultimate Module Creator
     */
    protected function _getTemplateProcessor()
    {
        if (null === $this->_templateProcessor) {
            $this->_templateProcessor = Mage::helper('catalog')->getPageTemplateProcessor();
        }
        return $this->_templateProcessor;
    }
    
    public function isAllowToShowPost($_post) {
        $_current_date = date('Y-m-d H:i:s');
        $_date = date('Y-m-d H:i:s', strtotime($_post->getPublishDate()));
        $_statusId = $_post->getPublishStatus();
        $_statusLabel = Mage::getModel('dls_dlsblog/post')
                // ->setStoreId($store_id)
                ->setData('publish_status', $_statusId)
                ->getAttributeText('publish_status');
        if ($_date > $_current_date || $_statusLabel != DLS_DLSBlog_Model_Post::APPROVED_STATUS){
            return false;
        }
        else
        {
            return true;
        }
    }
}
