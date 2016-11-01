<?php

class DLS_Blog_Block_Adminhtml_Taxonomy_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('taxonomy_');
        $form->setFieldNameSuffix('taxonomy');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
                'taxonomy_form', array('legend' => Mage::helper('dls_blog')->__('Category'))
        );
        $fieldset->addType(
                'image', Mage::getConfig()->getBlockClassName('dls_blog/adminhtml_taxonomy_helper_image')
        );
        if (!$this->getTaxonomy()->getId()) {
            $parentId = $this->getRequest()->getParam('parent');
            if (!$parentId) {
                $parentId = Mage::helper('dls_blog/taxonomy')->getRootTaxonomyId();
            }
            $fieldset->addField(
                    'path', 'hidden', array(
                'name' => 'path',
                'value' => $parentId
                    )
            );
        } else {
            $fieldset->addField(
                    'id', 'hidden', array(
                'name' => 'id',
                'value' => $this->getTaxonomy()->getId()
                    )
            );
            $fieldset->addField(
                    'path', 'hidden', array(
                'name' => 'path',
                'value' => $this->getTaxonomy()->getPath()
                    )
            );
        }

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
            'label' => Mage::helper('dls_blog')->__('Description'),
            'name' => 'description',
                )
        );

        $values = Mage::getResourceModel('dls_blog/layoutdesign_collection')
                ->toOptionArray();
        array_unshift($values, array('label' => '', 'value' => ''));

        $html = '<a href="{#url}" id="taxonomy_layoutdesign_id_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changeLayoutdesignIdLink() {
                if ($(\'taxonomy_layoutdesign_id\').value == \'\') {
                    $(\'taxonomy_layoutdesign_id_link\').hide();
                } else {
                    $(\'taxonomy_layoutdesign_id_link\').show();
                    var url = \'' . $this->getUrl('adminhtml/blog_layoutdesign/edit', array('id' => '{#id}', 'clear' => 1)) . '\';
                    var text = \'' . Mage::helper('core')->escapeHtml($this->__('View {#name}')) . '\';
                    var realUrl = url.replace(\'{#id}\', $(\'taxonomy_layoutdesign_id\').value);
                    $(\'taxonomy_layoutdesign_id_link\').href = realUrl;
                    $(\'taxonomy_layoutdesign_id_link\').innerHTML = text.replace(\'{#name}\', $(\'taxonomy_layoutdesign_id\').options[$(\'taxonomy_layoutdesign_id\').selectedIndex].innerHTML);
                }
            }
            $(\'taxonomy_layoutdesign_id\').observe(\'change\', changeLayoutdesignIdLink);
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

        $fieldset->addField(
                'small_image', 'image', array(
            'label' => Mage::helper('dls_blog')->__('Small Image'),
            'name' => 'small_image',
                )
        );

        $fieldset->addField(
                'large_image', 'image', array(
            'label' => Mage::helper('dls_blog')->__('Large image'),
            'name' => 'large_image',
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
        $form->addValues($this->getTaxonomy()->getData());
        return parent::_prepareForm();
    }

    public function getTaxonomy() {
        return Mage::registry('taxonomy');
    }

}
