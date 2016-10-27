<?php

/**
 * Taxonomy edit form
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Adminhtml_Taxonomy_Edit_Form extends DLS_DLSBlog_Block_Adminhtml_Taxonomy_Abstract
{
    /**
     * Additional buttons on taxonomy page
     * @var array
     */
    protected $_additionalButtons = array();
    /**
     * constructor
     *
     * set template
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('dls_dlsblog/taxonomy/edit/form.phtml');
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return DLS_DLSBlog_Block_Adminhtml_Taxonomy_Edit_Form
     * @author Ultimate Module Creator
     */
    protected function _prepareLayout()
    {
        $taxonomy = $this->getTaxonomy();
        $taxonomyId = (int)$taxonomy->getId();
        $this->setChild(
            'tabs',
            $this->getLayout()->createBlock('dls_dlsblog/adminhtml_taxonomy_edit_tabs', 'tabs')
        );
        $this->setChild(
            'save_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(
                    array(
                        'label'   => Mage::helper('dls_dlsblog')->__('Save Taxonomy'),
                        'onclick' => "taxonomySubmit('" . $this->getSaveUrl() . "', true)",
                        'class'   => 'save'
                    )
                )
        );
        // Delete button
        if (!in_array($taxonomyId, $this->getRootIds())) {
            $this->setChild(
                'delete_button',
                $this->getLayout()->createBlock('adminhtml/widget_button')
                    ->setData(
                        array(
                            'label'   => Mage::helper('dls_dlsblog')->__('Delete Taxonomy'),
                            'onclick' => "taxonomyDelete('" . $this->getUrl(
                                '*/*/delete',
                                array('_current' => true)
                            )
                            . "', true, {$taxonomyId})",
                            'class'   => 'delete'
                        )
                    )
            );
        }

        // Reset button
        $resetPath = $taxonomy ? '*/*/edit' : '*/*/add';
        $this->setChild(
            'reset_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(
                    array(
                        'label' => Mage::helper('dls_dlsblog')->__('Reset'),
                        'onclick'   => "taxonomyReset('".$this->getUrl(
                            $resetPath,
                            array('_current'=>true)
                        )
                        ."',true)"
                    )
                )
        );
        return parent::_prepareLayout();
    }

    /**
     * get html for delete button
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getDeleteButtonHtml()
    {
        return $this->getChildHtml('delete_button');
    }

    /**
     * get html for save button
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getSaveButtonHtml()
    {
        return $this->getChildHtml('save_button');
    }

    /**
     * get html for reset button
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getResetButtonHtml()
    {
        return $this->getChildHtml('reset_button');
    }

    /**
     * Retrieve additional buttons html
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getAdditionalButtonsHtml()
    {
        $html = '';
        foreach ($this->_additionalButtons as $childName) {
            $html .= $this->getChildHtml($childName);
        }
        return $html;
    }

    /**
     * Add additional button
     *
     * @param string $alias
     * @param array $config
     * @return DLS_DLSBlog_Block_Adminhtml_Taxonomy_Edit_Form
     * @author Ultimate Module Creator
     */
    public function addAdditionalButton($alias, $config)
    {
        if (isset($config['name'])) {
            $config['element_name'] = $config['name'];
        }
        $this->setChild(
            $alias . '_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')->addData($config)
        );
        $this->_additionalButtons[$alias] = $alias . '_button';
        return $this;
    }

    /**
     * Remove additional button
     *
     * @access public
     * @param string $alias
     * @return DLS_DLSBlog_Block_Adminhtml_Taxonomy_Edit_Form
     * @author Ultimate Module Creator
     */
    public function removeAdditionalButton($alias)
    {
        if (isset($this->_additionalButtons[$alias])) {
            $this->unsetChild($this->_additionalButtons[$alias]);
            unset($this->_additionalButtons[$alias]);
        }
        return $this;
    }

    /**
     * get html for tabs
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getTabsHtml()
    {
        return $this->getChildHtml('tabs');
    }

    /**
     * get the form header
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getHeader()
    {
        if ($this->getTaxonomyId()) {
            return $this->getTaxonomyName();
        } else {
            return Mage::helper('dls_dlsblog')->__('New Root Taxonomy');
        }
    }

    /**
     * get the delete url
     *
     * @access public
     * @param array $args
     * @return string
     * @author Ultimate Module Creator
     */
    public function getDeleteUrl(array $args = array())
    {
        $params = array('_current'=>true);
        $params = array_merge($params, $args);
        return $this->getUrl('*/*/delete', $params);
    }

    /**
     * Return URL for refresh input element 'path' in form
     *
     * @access public
     * @param array $args
     * @return string
     * @author Ultimate Module Creator
     */
    public function getRefreshPathUrl(array $args = array())
    {
        $params = array('_current'=>true);
        $params = array_merge($params, $args);
        return $this->getUrl('*/*/refreshPath', $params);
    }

    /**
     * check if request is ajax
     *
     * @access public
     * @return bool
     * @author Ultimate Module Creator
     */
    public function isAjax()
    {
        return Mage::app()->getRequest()->isXmlHttpRequest() || Mage::app()->getRequest()->getParam('isAjax');
    }

    /**
     * get  in json format
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getBlogsetsJson()
    {
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

    /**
     * get  in json format
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getFiltersJson()
    {
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

    /**
     * get  in json format
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getPostsJson()
    {
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
