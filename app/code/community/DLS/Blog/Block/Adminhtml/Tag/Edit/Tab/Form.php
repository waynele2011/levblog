<?php

class DLS_Blog_Block_Adminhtml_Tag_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('tag_');
        $form->setFieldNameSuffix('tag');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
                'tag_form', array('legend' => Mage::helper('dls_blog')->__('Tag'))
        );
        $values = Mage::getResourceModel('dls_blog/blogset_collection')
                ->toOptionArray();
        array_unshift($values, array('label' => '', 'value' => ''));

        $html = '<a href="{#url}" id="tag_blogset_id_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changeBlogsetIdLink() {
                if ($(\'tag_blogset_id\').value == \'\') {
                    $(\'tag_blogset_id_link\').hide();
                } else {
                    $(\'tag_blogset_id_link\').show();
                    var url = \'' . $this->getUrl('adminhtml/blog_blogset/edit', array('id' => '{#id}', 'clear' => 1)) . '\';
                    var text = \'' . Mage::helper('core')->escapeHtml($this->__('View {#name}')) . '\';
                    var realUrl = url.replace(\'{#id}\', $(\'tag_blogset_id\').value);
                    $(\'tag_blogset_id_link\').href = realUrl;
                    $(\'tag_blogset_id_link\').innerHTML = text.replace(\'{#name}\', $(\'tag_blogset_id\').options[$(\'tag_blogset_id\').selectedIndex].innerHTML);
                }
            }
            $(\'tag_blogset_id\').observe(\'change\', changeBlogsetIdLink);
            changeBlogsetIdLink();
            </script>';

        $fieldset->addField(
                'blogset_id', 'select', array(
            'label' => Mage::helper('dls_blog')->__('Blog'),
            'name' => 'blogset_id',
            'required' => false,
            'values' => $values,
            'after_element_html' => $html
                )
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
                'slug', 'text', array(
            'label' => Mage::helper('dls_blog')->__('Slug'),
            'name' => 'slug',
            'required' => true,
            'class' => 'required-entry',
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
        $formValues = Mage::registry('current_tag')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getTagData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getTagData());
            Mage::getSingleton('adminhtml/session')->setTagData(null);
        } elseif (Mage::registry('current_tag')) {
            $formValues = array_merge($formValues, Mage::registry('current_tag')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }

}
