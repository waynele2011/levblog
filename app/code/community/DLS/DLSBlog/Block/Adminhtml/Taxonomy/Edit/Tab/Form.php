<?php

/**
 * Taxonomy edit form tab
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Adminhtml_Taxonomy_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return DLS_DLSBlog_Block_Adminhtml_Taxonomy_Edit_Tab_Form
     * @author Ultimate Module Creator
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('taxonomy_');
        $form->setFieldNameSuffix('taxonomy');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'taxonomy_form',
            array('legend' => Mage::helper('dls_dlsblog')->__('Taxonomy'))
        );
        $fieldset->addType(
            'image',
            Mage::getConfig()->getBlockClassName('dls_dlsblog/adminhtml_taxonomy_helper_image')
        );
        if (!$this->getTaxonomy()->getId()) {
            $parentId = $this->getRequest()->getParam('parent');
            if (!$parentId) {
                $parentId = Mage::helper('dls_dlsblog/taxonomy')->getRootTaxonomyId();
            }
            $fieldset->addField(
                'path',
                'hidden',
                array(
                    'name'  => 'path',
                    'value' => $parentId
                )
            );
        } else {
            $fieldset->addField(
                'id',
                'hidden',
                array(
                    'name'  => 'id',
                    'value' => $this->getTaxonomy()->getId()
                )
            );
            $fieldset->addField(
                'path',
                'hidden',
                array(
                    'name'  => 'path',
                    'value' => $this->getTaxonomy()->getPath()
                )
            );
        }
        $values = Mage::getResourceModel('dls_dlsblog/layoutdesign_collection')
            ->toOptionArray();
        array_unshift($values, array('label' => '', 'value' => ''));

        $html = '<a href="{#url}" id="taxonomy_layoutdesign_id_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changeLayoutdesignIdLink() {
                if ($(\'taxonomy_layoutdesign_id\').value == \'\') {
                    $(\'taxonomy_layoutdesign_id_link\').hide();
                } else {
                    $(\'taxonomy_layoutdesign_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/dlsblog_layoutdesign/edit', array('id'=>'{#id}', 'clear'=>1)).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'taxonomy_layoutdesign_id\').value);
                    $(\'taxonomy_layoutdesign_id_link\').href = realUrl;
                    $(\'taxonomy_layoutdesign_id_link\').innerHTML = text.replace(\'{#name}\', $(\'taxonomy_layoutdesign_id\').options[$(\'taxonomy_layoutdesign_id\').selectedIndex].innerHTML);
                }
            }
            $(\'taxonomy_layoutdesign_id\').observe(\'change\', changeLayoutdesignIdLink);
            changeLayoutdesignIdLink();
            </script>';

        $fieldset->addField(
            'layoutdesign_id',
            'select',
            array(
                'label'     => Mage::helper('dls_dlsblog')->__('Layout design'),
                'name'      => 'layoutdesign_id',
                'required'  => false,
                'values'    => $values,
                'after_element_html' => $html
            )
        );

        $fieldset->addField(
            'name',
            'text',
            array(
                'label' => Mage::helper('dls_dlsblog')->__('Name'),
                'name'  => 'name',
                'required'  => true,
                'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'description',
            'textarea',
            array(
                'label' => Mage::helper('dls_dlsblog')->__('Description'),
                'name'  => 'description',

           )
        );

        $fieldset->addField(
            'small_image',
            'image',
            array(
                'label' => Mage::helper('dls_dlsblog')->__('Small Image'),
                'name'  => 'small_image',

           )
        );

        $fieldset->addField(
            'large_image',
            'image',
            array(
                'label' => Mage::helper('dls_dlsblog')->__('Large image'),
                'name'  => 'large_image',

           )
        );
        $fieldset->addField(
            'url_key',
            'text',
            array(
                'label' => Mage::helper('dls_dlsblog')->__('Url key'),
                'name'  => 'url_key',
                'note'  => Mage::helper('dls_dlsblog')->__('Relative to Website Base URL')
            )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('dls_dlsblog')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('dls_dlsblog')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('dls_dlsblog')->__('Disabled'),
                    ),
                ),
            )
        );
        $form->addValues($this->getTaxonomy()->getData());
        return parent::_prepareForm();
    }

    /**
     * get the current taxonomy
     *
     * @access public
     * @return DLS_DLSBlog_Model_Taxonomy
     */
    public function getTaxonomy()
    {
        return Mage::registry('taxonomy');
    }
}
