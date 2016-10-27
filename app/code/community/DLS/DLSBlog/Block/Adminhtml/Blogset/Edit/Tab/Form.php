<?php

/**
 * Blog setting edit form tab
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Adminhtml_Blogset_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return DLS_DLSBlog_Block_Adminhtml_Blogset_Edit_Tab_Form
     * @author Ultimate Module Creator
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('blogset_');
        $form->setFieldNameSuffix('blogset');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'blogset_form',
            array('legend' => Mage::helper('dls_dlsblog')->__('Blog setting'))
        );
        $values = Mage::getResourceModel('dls_dlsblog/layoutdesign_collection')
            ->toOptionArray();
        array_unshift($values, array('label' => '', 'value' => ''));

        $html = '<a href="{#url}" id="blogset_layoutdesign_id_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changeLayoutdesignIdLink() {
                if ($(\'blogset_layoutdesign_id\').value == \'\') {
                    $(\'blogset_layoutdesign_id_link\').hide();
                } else {
                    $(\'blogset_layoutdesign_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/dlsblog_layoutdesign/edit', array('id'=>'{#id}', 'clear'=>1)).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'blogset_layoutdesign_id\').value);
                    $(\'blogset_layoutdesign_id_link\').href = realUrl;
                    $(\'blogset_layoutdesign_id_link\').innerHTML = text.replace(\'{#name}\', $(\'blogset_layoutdesign_id\').options[$(\'blogset_layoutdesign_id\').selectedIndex].innerHTML);
                }
            }
            $(\'blogset_layoutdesign_id\').observe(\'change\', changeLayoutdesignIdLink);
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
                'label' => Mage::helper('dls_dlsblog')->__('Blog description'),
                'name'  => 'description',

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
