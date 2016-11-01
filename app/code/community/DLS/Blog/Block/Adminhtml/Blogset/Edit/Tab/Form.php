<?php

class DLS_Blog_Block_Adminhtml_Blogset_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('blogset_');
        $form->setFieldNameSuffix('blogset');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
                'blogset_form', array('legend' => Mage::helper('dls_blog')->__('Blog'))
        );
        $fieldset->addType(
                'image', Mage::getConfig()->getBlockClassName('dls_blog/adminhtml_blogset_helper_image')
        );

        $fieldset->addField(
                'name', 'text', array(
            'label' => Mage::helper('dls_blog')->__('Name'),
            'name' => 'name',
            'required' => true,
            'class' => 'required-entry',
                )
        );

        $fieldset->addField(
                'description', 'textarea', array(
            'label' => Mage::helper('dls_blog')->__('Blog description'),
            'name' => 'description',
                )
        );

        $values = Mage::getResourceModel('dls_blog/layoutdesign_collection')
                ->toOptionArray();
        array_unshift($values, array('label' => '', 'value' => ''));

        $html = '<a href="{#url}" id="blogset_layoutdesign_id_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changeLayoutdesignIdLink() {
                if ($(\'blogset_layoutdesign_id\').value == \'\') {
                    $(\'blogset_layoutdesign_id_link\').hide();
                } else {
                    $(\'blogset_layoutdesign_id_link\').show();
                    var url = \'' . $this->getUrl('adminhtml/blog_layoutdesign/edit', array('id' => '{#id}', 'clear' => 1)) . '\';
                    var text = \'' . Mage::helper('core')->escapeHtml($this->__('View {#name}')) . '\';
                    var realUrl = url.replace(\'{#id}\', $(\'blogset_layoutdesign_id\').value);
                    $(\'blogset_layoutdesign_id_link\').href = realUrl;
                    $(\'blogset_layoutdesign_id_link\').innerHTML = text.replace(\'{#name}\', $(\'blogset_layoutdesign_id\').options[$(\'blogset_layoutdesign_id\').selectedIndex].innerHTML);
                }
            }
            $(\'blogset_layoutdesign_id\').observe(\'change\', changeLayoutdesignIdLink);
            changeLayoutdesignIdLink();
            </script>';

        $fieldset->addField(
                'layoutdesign_id', 'select', array(
            'label' => Mage::helper('dls_blog')->__('Layout design'),
            'name' => 'layoutdesign_id',
            'required' => false,
            'values' => $values,
            'after_element_html' => $html
                )
        );

        $values = Mage::getResourceModel('dls_blog/filter_collection')
                ->toOptionArray();
        array_unshift($values, array('label' => '', 'value' => ''));

        $html = '<a href="{#url}" id="blogset_custom_default_filter_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changeFilterIdLink() {
                if ($(\'blogset_custom_default_filter\').value == \'\') {
                    $(\'blogset_custom_default_filter_link\').hide();
                } else {
                    $(\'blogset_custom_default_filter_link\').show();
                    var url = \'' . $this->getUrl('adminhtml/blog_filter/edit', array('id' => '{#id}', 'clear' => 1)) . '\';
                    var text = \'' . Mage::helper('core')->escapeHtml($this->__('View {#name}')) . '\';
                    var realUrl = url.replace(\'{#id}\', $(\'blogset_custom_default_filter\').value);
                    $(\'blogset_custom_default_filter_link\').href = realUrl;
                    $(\'blogset_custom_default_filter_link\').innerHTML = text.replace(\'{#name}\', $(\'blogset_custom_default_filter\').options[$(\'blogset_custom_default_filter\').selectedIndex].innerHTML);
                }
            }
            $(\'blogset_custom_default_filter\').observe(\'change\', changeFilterIdLink);
            changeFilterIdLink();
            </script>';

        $fieldset->addField(
                'custom_default_filter', 'select', array(
            'label' => Mage::helper('dls_blog')->__('Filter'),
            'name' => 'custom_default_filter',
            'required' => false,
            'values' => $values,
            'after_element_html' => $html
                )
        );

        $fieldset->addField(
                'logo', 'image', array(
            'label' => Mage::helper('dls_blog')->__('Logo image'),
            'name' => 'logo',
                )
        );
        $fieldset->addField(
                'url_key', 'text', array(
            'label' => Mage::helper('dls_blog')->__('Url key'),
            'name' => 'url_key',
            'note' => Mage::helper('dls_blog')->__('Relative to Website Base URL')
                )
        );
        $fieldset->addField(
                'status', 'select', array(
            'label' => Mage::helper('dls_blog')->__('Status'),
            'name' => 'status',
            'values' => array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('dls_blog')->__('Enabled'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('dls_blog')->__('Disabled'),
                ),
            ),
                )
        );
        $formValues = Mage::registry('current_blogset')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getBlogsetData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getBlogsetData());
            Mage::getSingleton('adminhtml/session')->setBlogsetData(null);
        } elseif (Mage::registry('current_blogset')) {
            $formValues = array_merge($formValues, Mage::registry('current_blogset')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }

}
