<?php

class DLS_Blog_Block_Adminhtml_Taxonomy_Widget_Chooser extends DLS_Blog_Block_Adminhtml_Taxonomy_Tree {

    protected $_selectedTaxonomies = array();

    public function __construct() {
        parent::__construct();
        $this->setTemplate('dls_blog/taxonomy/widget/tree.phtml');
    }

    public function setSelectedTaxonomies($selectedTaxonomies) {
        $this->_selectedTaxonomies = $selectedTaxonomies;
        return $this;
    }

    public function getSelectedTaxonomies() {
        return $this->_selectedTaxonomies;
    }

    public function prepareElementHtml(Varien_Data_Form_Element_Abstract $element) {
        $uniqId = Mage::helper('core')->uniqHash($element->getId());
        $sourceUrl = $this->getUrl(
                '*/blog_taxonomy_widget/chooser', array('uniq_id' => $uniqId, 'use_massaction' => false)
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
            $label = Mage::getSingleton('dls_blog/taxonomy')->load($taxonomyId)
                    ->getName();
            $chooser->setLabel($label);
        }
        $element->setData('after_element_html', $chooser->toHtml());
        return $element;
    }

    public function getNodeClickListener() {
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
                    ' . $chooserJsObject . '.setElementValue(node.attributes.id);
                    ' . $chooserJsObject . '.setElementLabel(node.text);
                    ' . $chooserJsObject . '.close();
                }
            ';
        }
        return $js;
    }

    protected function _getNodeJson($node, $level = 0) {
        $item = parent::_getNodeJson($node, $level);
        if (in_array($node->getId(), $this->getSelectedTaxonomies())) {
            $item['checked'] = true;
        }
        return $item;
    }

    public function getLoadTreeUrl($expanded = null) {
        return $this->getUrl(
                        '*/blog_taxonomy_widget/taxonomiesJson', array(
                    '_current' => true,
                    'uniq_id' => $this->getId(),
                    'use_massaction' => $this->getUseMassaction()
                        )
        );
    }

}
