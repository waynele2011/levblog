<?php

class DLS_Blog_Block_Adminhtml_Post_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        parent::__construct();
        $this->_blockGroup = 'dls_blog';
        $this->_controller = 'adminhtml_post';
        $this->_updateButton(
                'save', 'label', Mage::helper('dls_blog')->__('Save Post')
        );
        $this->_updateButton(
                'delete', 'label', Mage::helper('dls_blog')->__('Delete Post')
        );
        $this->_addButton(
                'saveandcontinue', array(
            'label' => Mage::helper('dls_blog')->__('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
                ), -100
        );
        $this->_updateButton(
                'save', 'onclick', 'saveAndSubmit()'
        );
        $this->_formScripts[] = "
            function strip(content){
                var div = document.createElement('div');
                div.innerHTML = content;
                result = div.textContent || div.innerText || '';
                return result.trim();
            }
            function copyAndSplitContent(content){
                content = content.split(' ',20).join(' ');
                return content.trim();
            }
            function saveAndContinueEdit() {
                document.getElementById('main_content').value = tinyMCE.get('main_content').getContent();
                document.getElementById('short_content').value = tinyMCE.get('short_content').getContent();
                var short_content_stripped = strip(copyAndSplitContent(tinyMCE.get('short_content').getContent()));
                if (short_content_stripped == '')
                {
                    var content = document.getElementById('main_content').value;
                    var stripped = copyAndSplitContent(strip(content));
                    document.getElementById('short_content').value = stripped + '...';
                    tinyMCE.get('short_content').setContent(stripped + '...');
                }
                editForm.submit($('edit_form').action+'back/edit/');
            } 
            function saveAndSubmit(){
                document.getElementById('main_content').value = tinyMCE.get('main_content').getContent();
                document.getElementById('short_content').value = tinyMCE.get('short_content').getContent();
                var short_content_stripped = strip(copyAndSplitContent(tinyMCE.get('short_content').getContent()));
                if (short_content_stripped == '')
                {
                    var content = document.getElementById('main_content').value;
                    var stripped = copyAndSplitContent(strip(content));
                    document.getElementById('short_content').value = stripped + '...';
                    tinyMCE.get('short_content').setContent(stripped + '...');
                }
                editForm.submit();
            }
        ";
    }

    public function getHeaderText() {
        if (Mage::registry('current_post') && Mage::registry('current_post')->getId()) {
            return Mage::helper('dls_blog')->__(
                            "Edit Post '%s'", $this->escapeHtml(Mage::registry('current_post')->getTitle())
            );
        } else {
            return Mage::helper('dls_blog')->__('Add Post');
        }
    }

}
