<?php

class DLS_Blog_Block_Adminhtml_Post_Comment_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $post = Mage::registry('current_post');
        $comment = Mage::registry('current_comment');
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('comment_');
        $form->setFieldNameSuffix('comment');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
                'comment_form', array('legend' => Mage::helper('dls_blog')->__('Comment'))
        );
        $fieldset->addField(
                'post_id', 'hidden', array(
            'name' => 'post_id',
            'after_element_html' => '<a href="' .
            Mage::helper('adminhtml')->getUrl(
                    'adminhtml/blog_post/edit', array(
                'id' => $post->getId()
                    )
            ) .
            '" target="_blank">' .
            Mage::helper('dls_blog')->__('Post') .
            ' : ' . $post->getTitle() . '</a>'
                )
        );
        $fieldset->addField(
                'title', 'text', array(
            'label' => Mage::helper('dls_blog')->__('Title'),
            'name' => 'title',
            'required' => true,
            'class' => 'required-entry',
                )
        );
        $fieldset->addField(
                'comment', 'textarea', array(
            'label' => Mage::helper('dls_blog')->__('Comment'),
            'name' => 'comment',
            'required' => true,
            'class' => 'required-entry',
                )
        );
        $fieldset->addField(
                'status', 'select', array(
            'label' => Mage::helper('dls_blog')->__('Status'),
            'name' => 'status',
            'required' => true,
            'class' => 'required-entry',
            'values' => array(
                array(
                    'value' => DLS_Blog_Model_Post_Comment::STATUS_PENDING,
                    'label' => Mage::helper('dls_blog')->__('Pending'),
                ),
                array(
                    'value' => DLS_Blog_Model_Post_Comment::STATUS_APPROVED,
                    'label' => Mage::helper('dls_blog')->__('Approved'),
                ),
                array(
                    'value' => DLS_Blog_Model_Post_Comment::STATUS_REJECTED,
                    'label' => Mage::helper('dls_blog')->__('Rejected'),
                ),
            ),
                )
        );
        $configuration = array(
            'label' => Mage::helper('dls_blog')->__('Poster name'),
            'name' => 'name',
            'required' => true,
            'class' => 'required-entry',
        );
        if ($comment->getCustomerId()) {
            $configuration['after_element_html'] = '<a href="' .
                    Mage::helper('adminhtml')->getUrl(
                            'adminhtml/customer/edit', array(
                        'id' => $comment->getCustomerId()
                            )
                    ) .
                    '" target="_blank">' .
                    Mage::helper('dls_blog')->__('Customer profile') . '</a>';
        }
        $fieldset->addField('name', 'text', $configuration);
        $fieldset->addField(
                'email', 'text', array(
            'label' => Mage::helper('dls_blog')->__('Poster e-mail'),
            'name' => 'email',
            'required' => true,
            'class' => 'required-entry',
                )
        );
        $fieldset->addField(
                'customer_id', 'hidden', array(
            'name' => 'customer_id',
                )
        );
        if (Mage::app()->isSingleStoreMode()) {
            $fieldset->addField(
                    'store_id', 'hidden', array(
                'name' => 'stores[]',
                'value' => Mage::app()->getStore(true)->getId()
                    )
            );
            Mage::registry('current_comment')->setStoreId(Mage::app()->getStore(true)->getId());
        }
        $form->addValues($this->getComment()->getData());
        return parent::_prepareForm();
    }

    public function getComment() {
        return Mage::registry('current_comment');
    }

}
