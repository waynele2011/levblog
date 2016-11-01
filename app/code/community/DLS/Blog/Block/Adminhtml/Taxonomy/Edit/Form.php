<?php

class DLS_Blog_Block_Adminhtml_Taxonomy_Edit_Form extends DLS_Blog_Block_Adminhtml_Taxonomy_Abstract {

    protected $_additionalButtons = array();

    public function __construct() {
        parent::__construct();
        $this->setTemplate('dls_blog/taxonomy/edit/form.phtml');
    }

    protected function _prepareLayout() {
        $taxonomy = $this->getTaxonomy();
        $taxonomyId = (int) $taxonomy->getId();
        $this->setChild(
                'tabs', $this->getLayout()->createBlock('dls_blog/adminhtml_taxonomy_edit_tabs', 'tabs')
        );
        $this->setChild(
                'save_button', $this->getLayout()->createBlock('adminhtml/widget_button')
                        ->setData(
                                array(
                                    'label' => Mage::helper('dls_blog')->__('Save Category'),
                                    'onclick' => "taxonomySubmit('" . $this->getSaveUrl() . "', true)",
                                    'class' => 'save'
                                )
                        )
        );
        // Delete button
        if (!in_array($taxonomyId, $this->getRootIds())) {
            $this->setChild(
                    'delete_button', $this->getLayout()->createBlock('adminhtml/widget_button')
                            ->setData(
                                    array(
                                        'label' => Mage::helper('dls_blog')->__('Delete Category'),
                                        'onclick' => "taxonomyDelete('" . $this->getUrl(
                                                '*/*/delete', array('_current' => true)
                                        )
                                        . "', true, {$taxonomyId})",
                                        'class' => 'delete'
                                    )
                            )
            );
        }

        // Reset button
        $resetPath = $taxonomy ? '*/*/edit' : '*/*/add';
        $this->setChild(
                'reset_button', $this->getLayout()->createBlock('adminhtml/widget_button')
                        ->setData(
                                array(
                                    'label' => Mage::helper('dls_blog')->__('Reset'),
                                    'onclick' => "taxonomyReset('" . $this->getUrl(
                                            $resetPath, array('_current' => true)
                                    )
                                    . "',true)"
                                )
                        )
        );
        return parent::_prepareLayout();
    }

    public function getDeleteButtonHtml() {
        return $this->getChildHtml('delete_button');
    }

    public function getSaveButtonHtml() {
        return $this->getChildHtml('save_button');
    }

    public function getResetButtonHtml() {
        return $this->getChildHtml('reset_button');
    }

    public function getAdditionalButtonsHtml() {
        $html = '';
        foreach ($this->_additionalButtons as $childName) {
            $html .= $this->getChildHtml($childName);
        }
        return $html;
    }

    public function addAdditionalButton($alias, $config) {
        if (isset($config['name'])) {
            $config['element_name'] = $config['name'];
        }
        $this->setChild(
                $alias . '_button', $this->getLayout()->createBlock('adminhtml/widget_button')->addData($config)
        );
        $this->_additionalButtons[$alias] = $alias . '_button';
        return $this;
    }

    public function removeAdditionalButton($alias) {
        if (isset($this->_additionalButtons[$alias])) {
            $this->unsetChild($this->_additionalButtons[$alias]);
            unset($this->_additionalButtons[$alias]);
        }
        return $this;
    }

    public function getTabsHtml() {
        return $this->getChildHtml('tabs');
    }

    public function getHeader() {
        if ($this->getTaxonomyId()) {
            return $this->getTaxonomyName();
        } else {
            return Mage::helper('dls_blog')->__('New Root Category');
        }
    }

    public function getDeleteUrl(array $args = array()) {
        $params = array('_current' => true);
        $params = array_merge($params, $args);
        return $this->getUrl('*/*/delete', $params);
    }

    public function getRefreshPathUrl(array $args = array()) {
        $params = array('_current' => true);
        $params = array_merge($params, $args);
        return $this->getUrl('*/*/refreshPath', $params);
    }

    public function isAjax() {
        return Mage::app()->getRequest()->isXmlHttpRequest() || Mage::app()->getRequest()->getParam('isAjax');
    }

    public function getBlogsetsJson() {
        $blogsets = $this->getTaxonomy()->getSelectedBlogsets();
        if (!empty($blogsets)) {
            $positions = array();
            foreach ($blogsets as $blogset) {
                $positions[$blogset->getId()] = $blogset->getPosition();
            }
            return Mage::helper('core')->jsonEncode($positions);
        }
        return '{}';
    }

    public function getFiltersJson() {
        $filters = $this->getTaxonomy()->getSelectedFilters();
        if (!empty($filters)) {
            $positions = array();
            foreach ($filters as $filter) {
                $positions[$filter->getId()] = $filter->getPosition();
            }
            return Mage::helper('core')->jsonEncode($positions);
        }
        return '{}';
    }

    public function getPostsJson() {
        $posts = $this->getTaxonomy()->getSelectedPosts();
        if (!empty($posts)) {
            $positions = array();
            foreach ($posts as $post) {
                $positions[$post->getId()] = $post->getPosition();
            }
            return Mage::helper('core')->jsonEncode($positions);
        }
        return '{}';
    }

}
