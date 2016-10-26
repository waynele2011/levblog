<?php

class DLS_DLSBlog_Block_Adminhtml_Filter_Widget_Chooser extends Mage_Adminhtml_Block_Widget_Grid {

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
                '*/dlsblog_filter_widget/chooser', array('uniq_id' => $uniqId)
        );
        $chooser = $this->getLayout()->createBlock('widget/adminhtml_widget_chooser')
                ->setElement($element)
                ->setTranslationHelper($this->getTranslationHelper())
                ->setConfig($this->getConfig())
                ->setFieldsetId($this->getFieldsetId())
                ->setSourceUrl($sourceUrl)
                ->setUniqId($uniqId);
        if ($element->getValue()) {
            $filter = Mage::getModel('dls_dlsblog/filter')->load($element->getValue());
            if ($filter->getId()) {
                $chooser->setLabel($filter->getName());
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
                var filterId = trElement.down("td").innerHTML.replace(/^\s+|\s+$/g,"");
                var filterTitle = trElement.down("td").next().innerHTML;
                ' . $chooserJsObject . '.setElementValue(filterId);
                ' . $chooserJsObject . '.setElementLabel(filterTitle);
                ' . $chooserJsObject . '.close();
            }
        ';
        return $js;
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('dls_dlsblog/filter')->getCollection();
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
                        'adminhtml/dlsblog_filter_widget/chooser', array('_current' => true)
        );
    }

    protected function _afterLoadCollection() {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

}
