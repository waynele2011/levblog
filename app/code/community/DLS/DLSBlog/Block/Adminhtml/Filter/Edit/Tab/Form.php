<?php

/**
 * Filter edit form tab
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Adminhtml_Filter_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return DLS_DLSBlog_Block_Adminhtml_Filter_Edit_Tab_Form
     * @author Ultimate Module Creator
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('filter_');
        $form->setFieldNameSuffix('filter');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'filter_form',
            array('legend' => Mage::helper('dls_dlsblog')->__('Filter'))
        );
        $values = Mage::getResourceModel('dls_dlsblog/blogset_collection')
            ->toOptionArray();
        array_unshift($values, array('label' => '', 'value' => ''));

        $html = '<a href="{#url}" id="filter_blogset_id_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changeBlogsetIdLink() {
                if ($(\'filter_blogset_id\').value == \'\') {
                    $(\'filter_blogset_id_link\').hide();
                } else {
                    $(\'filter_blogset_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/dlsblog_blogset/edit', array('id'=>'{#id}', 'clear'=>1)).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'filter_blogset_id\').value);
                    $(\'filter_blogset_id_link\').href = realUrl;
                    $(\'filter_blogset_id_link\').innerHTML = text.replace(\'{#name}\', $(\'filter_blogset_id\').options[$(\'filter_blogset_id\').selectedIndex].innerHTML);
                }
            }
            $(\'filter_blogset_id\').observe(\'change\', changeBlogsetIdLink);
            changeBlogsetIdLink();
            </script>';

        $fieldset->addField(
            'blogset_id',
            'select',
            array(
                'label'     => Mage::helper('dls_dlsblog')->__('Blog setting'),
                'name'      => 'blogset_id',
                'required'  => false,
                'values'    => $values,
                'after_element_html' => $html
            )
        );
        $values = Mage::getResourceModel('dls_dlsblog/layoutdesign_collection')
            ->toOptionArray();
        array_unshift($values, array('label' => '', 'value' => ''));

        $html = '<a href="{#url}" id="filter_layoutdesign_id_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changeLayoutdesignIdLink() {
                if ($(\'filter_layoutdesign_id\').value == \'\') {
                    $(\'filter_layoutdesign_id_link\').hide();
                } else {
                    $(\'filter_layoutdesign_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/dlsblog_layoutdesign/edit', array('id'=>'{#id}', 'clear'=>1)).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'filter_layoutdesign_id\').value);
                    $(\'filter_layoutdesign_id_link\').href = realUrl;
                    $(\'filter_layoutdesign_id_link\').innerHTML = text.replace(\'{#name}\', $(\'filter_layoutdesign_id\').options[$(\'filter_layoutdesign_id\').selectedIndex].innerHTML);
                }
            }
            $(\'filter_layoutdesign_id\').observe(\'change\', changeLayoutdesignIdLink);
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
            'exposed',
            'select',
            array(
                'label' => Mage::helper('dls_dlsblog')->__('Exposed filter'),
                'name'  => 'exposed',
                'required'  => true,
                'class' => 'required-entry',

            'values'=> array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('dls_dlsblog')->__('Yes'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('dls_dlsblog')->__('No'),
                ),
            ),
           )
        );

        $fieldset->addField(
            'type',
            'select',
            array(
                'label' => Mage::helper('dls_dlsblog')->__('Filter type'),
                'name'  => 'type',
                'required'  => true,
                'class' => 'required-entry',

                'values'=> Mage::getModel('dls_dlsblog/filter_attribute_source_type')->getAllOptions(true),
           )
        );

        $fieldset->addField(
            'condition_code',
            'textarea',
            array(
                'label' => Mage::helper('dls_dlsblog')->__('Condition'),
                'name'  => 'condition_code',

           )
        );

        $fieldset->addField(
            'sort_code',
            'textarea',
            array(
                'label' => Mage::helper('dls_dlsblog')->__('Sorts'),
                'name'  => 'sort_code',
                'required'  => true,
                'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'paging_code',
            'textarea',
            array(
                'label' => Mage::helper('dls_dlsblog')->__('Paging'),
                'name'  => 'paging_code',

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
        $formValues = Mage::registry('current_filter')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getFilterData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getFilterData());
            Mage::getSingleton('adminhtml/session')->setFilterData(null);
        } elseif (Mage::registry('current_filter')) {
            $formValues = array_merge($formValues, Mage::registry('current_filter')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
