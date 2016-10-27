<?php

class DLS_DLSBlog_Block_Adminhtml_Taxonomy_Widget_Chooser extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct($arguments = array()) {
        parent::__construct($arguments);
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
        $this->setDefaultFilter(array('chooser_status' => '1'));
    }

    public function prepareElementHtml(Varien_Data_Form_Element_Abstract $element) {
        $uniqId = Mage::helper('core')->uniqHash($element->getId());
        $sourceUrl = $this->getUrl(
                '*/dlsblog_taxonomy_widget/chooser', array('uniq_id' => $uniqId)
        );
        $chooser = $this->getLayout()->createBlock('widget/adminhtml_widget_chooser')
                ->setElement($element)
                ->setTranslationHelper($this->getTranslationHelper())
                ->setConfig($this->getConfig())
                ->setFieldsetId($this->getFieldsetId())
                ->setSourceUrl($sourceUrl)
                ->setUniqId($uniqId);
        if ($element->getValue()) {
            $taxonomy = Mage::getModel('dls_dlsblog/taxonomy')->load($element->getValue());
            if ($taxonomy->getId()) {
                $chooser->setLabel($taxonomy->getName());
            }
        }
        $element->setData('after_element_html', $chooser->toHtml());
        return $element;
    }

    public function getRowClickCallback() {
        $chooserJsObject = $this->getId();
        $js = '
            function (grid, event) {
                var trElement = Event.findElement(event, "tr");
                var taxonomyId = trElement.down("td").innerHTML.replace(/^\s+|\s+$/g,"");
                var taxonomyTitle = trElement.down("td").next().innerHTML;
                ' . $chooserJsObject . '.setElementValue(taxonomyId);
                ' . $chooserJsObject . '.setElementLabel(taxonomyTitle);
                ' . $chooserJsObject . '.close();
            }
        ';
        return $js;
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('dls_dlsblog/taxonomy')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn(
                'chooser_id', array(
            'header' => Mage::helper('dls_dlsblog')->__('Id'),
            'align' => 'right',
            'index' => 'entity_id',
            'type' => 'number',
            'width' => 50
                )
        );

        $this->addColumn(
                'chooser_name', array(
            'header' => Mage::helper('dls_dlsblog')->__('Name'),
            'align' => 'left',
            'index' => 'name',
                )
        );
        $this->addColumn(
                'chooser_status', array(
            'header' => Mage::helper('dls_dlsblog')->__('Status'),
            'index' => 'status',
            'type' => 'options',
            'options' => array(
                0 => Mage::helper('dls_dlsblog')->__('Disabled'),
                1 => Mage::helper('dls_dlsblog')->__('Enabled')
            ),
                )
        );
        return parent::_prepareColumns();
    }

    public function getGridUrl() {
        return $this->getUrl(
                        'adminhtml/dlsblog_taxonomy_widget/chooser', array('_current' => true)
        );
    }

    protected function _afterLoadCollection() {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

}
