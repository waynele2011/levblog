<?php

/**
 * Category admin widget chooser
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */

class DLS_DLSBlog_Block_Adminhtml_Taxonomy_Widget_Chooser extends DLS_DLSBlog_Block_Adminhtml_Taxonomy_Tree
{
    protected $_selectedTaxonomies = array();

    /**
     * Block construction
     * Defines tree template and init tree params
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('dls_dlsblog/taxonomy/widget/tree.phtml');
    }

    /**
     * Setter
     *
     * @access public
     * @param array $selectedTaxonomies
     * @return DLS_DLSBlog_Block_Adminhtml_Taxonomy_Widget_Chooser
     * @author Ultimate Module Creator
     */
    public function setSelectedTaxonomies($selectedTaxonomies)
    {
        $this->_selectedTaxonomies = $selectedTaxonomies;
        return $this;
    }

    /**
     * Getter
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getSelectedTaxonomies()
    {
        return $this->_selectedTaxonomies;
    }

    /**
     * Prepare chooser element HTML
     *
     * @access public
     * @param Varien_Data_Form_Element_Abstract $element Form Element
     * @return Varien_Data_Form_Element_Abstract
     * @author Ultimate Module Creator
     */
    public function prepareElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $uniqId = Mage::helper('core')->uniqHash($element->getId());
        $sourceUrl = $this->getUrl(
            '*/dlsblog_taxonomy_widget/chooser',
            array('uniq_id' => $uniqId, 'use_massaction' => false)
        );
        $chooser = $this->getLayout()->createBlock('widget/adminhtml_widget_chooser')
            ->setElement($element)
            ->setTranslationHelper($this->getTranslationHelper())
            ->setConfig($this->getConfig())
            ->setFieldsetId($this->getFieldsetId())
            ->setSourceUrl($sourceUrl)
            ->setUniqId($uniqId);
        $value = $element->getValue();
        $taxonomyId = false;
        if ($value) {
            $taxonomyId = $value;
        }
        if ($taxonomyId) {
            $label = Mage::getSingleton('dls_dlsblog/taxonomy')->load($taxonomyId)
                ->getName();
            $chooser->setLabel($label);
        }
        $element->setData('after_element_html', $chooser->toHtml());
        return $element;
    }

    /**
     * onClick listener js function
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getNodeClickListener()
    {
        if ($this->getData('node_click_listener')) {
            return $this->getData('node_click_listener');
        }
        if ($this->getUseMassaction()) {
            $js = '
                function (node, e) {
                    if (node.ui.toggleCheck) {
                        node.ui.toggleCheck(true);
                    }
                }
            ';
        } else {
            $chooserJsObject = $this->getId();
            $js = '
                function (node, e) {
                    '.$chooserJsObject.'.setElementValue(node.attributes.id);
                    '.$chooserJsObject.'.setElementLabel(node.text);
                    '.$chooserJsObject.'.close();
                }
            ';
        }
        return $js;
    }

    /**
     * Get JSON of a tree node or an associative array
     *
     * @access protected
     * @param Varien_Data_Tree_Node|array $node
     * @param int $level
     * @return string
     * @author Ultimate Module Creator
     */
    protected function _getNodeJson($node, $level = 0)
    {
        $item = parent::_getNodeJson($node, $level);
        if (in_array($node->getId(), $this->getSelectedTaxonomies())) {
            $item['checked'] = true;
        }
        return $item;
    }

    /**
     * Tree JSON source URL
     *
     * @access public
     * @param mixed $expanded
     * @return string
     * @author Ultimate Module Creator
     */
    public function getLoadTreeUrl($expanded=null)
    {
        return $this->getUrl(
            '*/dlsblog_taxonomy_widget/taxonomiesJson',
            array(
                '_current'=>true,
                'uniq_id' => $this->getId(),
                'use_massaction' => $this->getUseMassaction()
            )
        );
    }
}
