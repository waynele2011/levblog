<?php
 
/**
 * Post admin edit tab attributes block
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
*/
class DLS_DLSBlog_Block_Adminhtml_Post_Edit_Tab_Attributes extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the attributes for the form
     *
     * @access protected
     * @return void
     * @see Mage_Adminhtml_Block_Widget_Form::_prepareForm()
     * @author Ultimate Module Creator
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setDataObject(Mage::registry('current_post'));
        $fieldset = $form->addFieldset(
            'info',
            array(
                'legend' => Mage::helper('dls_dlsblog')->__('Post Information'),
                'class' => 'fieldset-wide',
            )
        );
        $attributes = $this->getAttributes();
        foreach ($attributes as $attribute) {
            $attribute->setEntity(Mage::getResourceModel('dls_dlsblog/post'));
        }

        $post = Mage::registry('current_post');
        $selectedTags = $post->getSelectedTags();
        $tagsValue = array();
        foreach ($selectedTags as $tag){
            $tagsValue[] = $tag->getName();
        }
        
        
        $this->_setFieldset($attributes, $fieldset, array());
        $fieldset->addType('tags_autocomplete', 'DLS_DLSBlog_Block_Adminhtml_Post_Renderer_TagsAutocomplete');
        $fieldset->addField(
            'tags_autocomplete',
            'tags_autocomplete',
            array(
                'label' => Mage::helper('dls_dlsblog')->__('Tags'),
                'name'  => 'tags_autocomplete',
                'value' => implode(',',$tagsValue),
           )
        );

        $fieldset->addField(
            'tags_custom_hidden',
            'hidden',
            array(
                'name'  => 'tags_custom_hidden',
                'class'  => 'tags-custom-hidden',
                'value' => implode(',',$tagsValue),
           )
        );
        
        $formValues = Mage::registry('current_post')->getData();
        if (!Mage::registry('current_post')->getId()) {
            foreach ($attributes as $attribute) {
                if (!isset($formValues[$attribute->getAttributeCode()])) {
                    $formValues[$attribute->getAttributeCode()] = $attribute->getDefaultValue();
                }
            }
        }
        $form->addValues($formValues);
        $form->setFieldNameSuffix('post');
        $this->setForm($form);
    }

    /**
     * prepare layout
     *
     * @access protected
     * @return void
     * @see Mage_Adminhtml_Block_Widget_Form::_prepareLayout()
     * @author Ultimate Module Creator
     */
    protected function _prepareLayout()
    {
        Varien_Data_Form::setElementRenderer(
            $this->getLayout()->createBlock('adminhtml/widget_form_renderer_element')
        );
        Varien_Data_Form::setFieldsetRenderer(
            $this->getLayout()->createBlock('adminhtml/widget_form_renderer_fieldset')
        );
        Varien_Data_Form::setFieldsetElementRenderer(
            $this->getLayout()->createBlock('dls_dlsblog/adminhtml_dlsblog_renderer_fieldset_element')
        );
    }

    /**
     * get the additional element types for form
     *
     * @access protected
     * @return array()
     * @see Mage_Adminhtml_Block_Widget_Form::_getAdditionalElementTypes()
     * @author Ultimate Module Creator
     */
    protected function _getAdditionalElementTypes()
    {
        return array(
            'file'     => Mage::getConfig()->getBlockClassName(
                'dls_dlsblog/adminhtml_post_helper_file'
            ),
            'image'    => Mage::getConfig()->getBlockClassName(
                'dls_dlsblog/adminhtml_post_helper_image'
            ),
            'textarea' => Mage::getConfig()->getBlockClassName(
                'adminhtml/catalog_helper_form_wysiwyg'
            )
        );
    }

    /**
     * get current entity
     *
     * @access protected
     * @return DLS_DLSBlog_Model_Post
     * @author Ultimate Module Creator
     */
    public function getPost()
    {
        return Mage::registry('current_post');
    }

    /**
     * get after element html
     *
     * @access protected
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     * @author Ultimate Module Creator
     */
    protected function _getAdditionalElementHtml($element)
    {
        if ($element->getName() == 'blogset_id') {
            $html = '<a href="{#url}" id="blogset_id_link" target="_blank"></a>';
            $html .= '<script type="text/javascript">
            function changeBlogsetIdLink() {
                if ($(\'blogset_id\').value == \'\') {
                    $(\'blogset_id_link\').hide();
                } else {
                    $(\'blogset_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/dlsblog_blogset/edit', array('id'=>'{#id}', 'clear'=>1)).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'blogset_id\').value);
                    $(\'blogset_id_link\').href = realUrl;
                    $(\'blogset_id_link\').innerHTML = text.replace(\'{#name}\', $(\'blogset_id\').options[$(\'blogset_id\').selectedIndex].innerHTML);
                }
            }
            $(\'blogset_id\').observe(\'change\', changeBlogsetIdLink);
            changeBlogsetIdLink();
            </script>';
            return $html;
        }
        if ($element->getName() == 'layoutdesign_id') {
            $html = '<a href="{#url}" id="layoutdesign_id_link" target="_blank"></a>';
            $html .= '<script type="text/javascript">
            function changeLayoutdesignIdLink() {
                if ($(\'layoutdesign_id\').value == \'\') {
                    $(\'layoutdesign_id_link\').hide();
                } else {
                    $(\'layoutdesign_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/dlsblog_layoutdesign/edit', array('id'=>'{#id}', 'clear'=>1)).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'layoutdesign_id\').value);
                    $(\'layoutdesign_id_link\').href = realUrl;
                    $(\'layoutdesign_id_link\').innerHTML = text.replace(\'{#name}\', $(\'layoutdesign_id\').options[$(\'layoutdesign_id\').selectedIndex].innerHTML);
                }
            }
            $(\'layoutdesign_id\').observe(\'change\', changeLayoutdesignIdLink);
            changeLayoutdesignIdLink();
            </script>';
            return $html;
        }
        return '';
    }
}
