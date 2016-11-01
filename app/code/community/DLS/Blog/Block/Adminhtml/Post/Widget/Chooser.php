<?php

class DLS_Blog_Block_Adminhtml_Post_Widget_Chooser extends Mage_Adminhtml_Block_Widget_Grid {

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
                '*/blog_post_widget/chooser', array('uniq_id' => $uniqId)
        );
        $chooser = $this->getLayout()->createBlock('widget/adminhtml_widget_chooser')
                ->setElement($element)
                ->setTranslationHelper($this->getTranslationHelper())
                ->setConfig($this->getConfig())
                ->setFieldsetId($this->getFieldsetId())
                ->setSourceUrl($sourceUrl)
                ->setUniqId($uniqId);
        if ($element->getValue()) {
            $post = Mage::getModel('dls_blog/post')->load($element->getValue());
            if ($post->getId()) {
                $chooser->setLabel($post->getTitle());
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
                var postId = trElement.down("td").innerHTML.replace(/^\s+|\s+$/g,"");
                var postTitle = trElement.down("td").next().innerHTML;
                ' . $chooserJsObject . '.setElementValue(postId);
                ' . $chooserJsObject . '.setElementLabel(postTitle);
                ' . $chooserJsObject . '.close();
            }
        ';
        return $js;
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('dls_blog/post')->getCollection();
        $collection->addAttributeToSelect('title');
        $collection->addAttributeToSelect('status');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn(
                'chooser_id', array(
            'header' => Mage::helper('dls_blog')->__('Id'),
            'align' => 'right',
            'index' => 'entity_id',
            'type' => 'number',
            'width' => 50
                )
        );

        $this->addColumn(
                'chooser_title', array(
            'header' => Mage::helper('dls_blog')->__('Title'),
            'align' => 'left',
            'index' => 'title',
                )
        );
        $this->addColumn(
                'chooser_status', array(
            'header' => Mage::helper('dls_blog')->__('Status'),
            'index' => 'status',
            'type' => 'options',
            'options' => array(
                0 => Mage::helper('dls_blog')->__('Disabled'),
                1 => Mage::helper('dls_blog')->__('Enabled')
            ),
                )
        );
        return parent::_prepareColumns();
    }

    public function getGridUrl() {
        return $this->getUrl(
                        'adminhtml/blog_post_widget/chooser', array('_current' => true)
        );
    }

}
