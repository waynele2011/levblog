<?php

class DLS_DLSBlog_Block_Adminhtml_Post_Edit_Tab_Attributes extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $form->setDataObject(Mage::registry('current_post'));
        $fieldset = $form->addFieldset(
                'info', array(
            'legend' => Mage::helper('dls_dlsblog')->__('Post Information'),
            'class' => 'fieldset-wide',
                )
        );
        $attributes = $this->getAttributes();
        foreach ($attributes as $attribute) {
            $attribute->setEntity(Mage::getResourceModel('dls_dlsblog/post'));
        }
        $this->_setFieldset($attributes, $fieldset, array());
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

    protected function _prepareLayout() {
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

    protected function _getAdditionalElementTypes() {
        return array(
            'file' => Mage::getConfig()->getBlockClassName(
                    'dls_dlsblog/adminhtml_post_helper_file'
            ),
            'image' => Mage::getConfig()->getBlockClassName(
                    'dls_dlsblog/adminhtml_post_helper_image'
            ),
            'textarea' => Mage::getConfig()->getBlockClassName(
                    'adminhtml/catalog_helper_form_wysiwyg'
            )
        );
    }

    public function getPost() {
        return Mage::registry('current_post');
    }

    protected function _getAdditionalElementHtml($element) {
        if ($element->getName() == 'blogset_id') {
            $html = '<a href="{#url}" id="blogset_id_link" target="_blank"></a>';
            $html .= '<script type="text/javascript">
            function changeBlogsetIdLink() {
                if ($(\'blogset_id\').value == \'\') {
                    $(\'blogset_id_link\').hide();
                } else {
                    $(\'blogset_id_link\').show();
                    var url = \'' . $this->getUrl('adminhtml/dlsblog_blogset/edit', array('id' => '{#id}', 'clear' => 1)) . '\';
                    var text = \'' . Mage::helper('core')->escapeHtml($this->__('View {#name}')) . '\';
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
        if ($element->getName() == 'taxonomy_id') {
            $html = '<a href="{#url}" id="taxonomy_id_link" target="_blank"></a>';
            $html .= '<script type="text/javascript">
            function changeTaxonomyIdLink() {
                if ($(\'taxonomy_id\').value == \'\') {
                    $(\'taxonomy_id_link\').hide();
                } else {
                    $(\'taxonomy_id_link\').show();
                    var url = \'' . $this->getUrl('adminhtml/dlsblog_taxonomy/edit', array('id' => '{#id}', 'clear' => 1)) . '\';
                    var text = \'' . Mage::helper('core')->escapeHtml($this->__('View {#name}')) . '\';
                    var realUrl = url.replace(\'{#id}\', $(\'taxonomy_id\').value);
                    $(\'taxonomy_id_link\').href = realUrl;
                    $(\'taxonomy_id_link\').innerHTML = text.replace(\'{#name}\', $(\'taxonomy_id\').options[$(\'taxonomy_id\').selectedIndex].innerHTML);
                }
            }
            $(\'taxonomy_id\').observe(\'change\', changeTaxonomyIdLink);
            changeTaxonomyIdLink();
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
                    var url = \'' . $this->getUrl('adminhtml/dlsblog_layoutdesign/edit', array('id' => '{#id}', 'clear' => 1)) . '\';
                    var text = \'' . Mage::helper('core')->escapeHtml($this->__('View {#name}')) . '\';
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
