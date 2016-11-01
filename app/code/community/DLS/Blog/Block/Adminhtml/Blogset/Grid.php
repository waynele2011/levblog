<?php

class DLS_Blog_Block_Adminhtml_Blogset_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('blogsetGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('dls_blog/blogset')
                ->getCollection();

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn(
                'entity_id', array(
            'header' => Mage::helper('dls_blog')->__('Id'),
            'index' => 'entity_id',
            'type' => 'number'
                )
        );
        $this->addColumn(
                'layoutdesign_id', array(
            'header' => Mage::helper('dls_blog')->__('Layout Design'),
            'index' => 'layoutdesign_id',
            'type' => 'options',
            'options' => Mage::getResourceModel('dls_blog/layoutdesign_collection')
                    ->toOptionHash(),
            'renderer' => 'dls_blog/adminhtml_helper_column_renderer_parent',
            'params' => array(
                'id' => 'getLayoutdesignId'
            ),
            'base_link' => 'adminhtml/blog_layoutdesign/edit'
                )
        );
        $this->addColumn(
                'name', array(
            'header' => Mage::helper('dls_blog')->__('Name'),
            'align' => 'left',
            'index' => 'name',
                )
        );

        $this->addColumn(
                'status', array(
            'header' => Mage::helper('dls_blog')->__('Status'),
            'index' => 'status',
            'type' => 'options',
            'options' => array(
                '1' => Mage::helper('dls_blog')->__('Enabled'),
                '0' => Mage::helper('dls_blog')->__('Disabled'),
            )
                )
        );
        $this->addColumn(
                'url_key', array(
            'header' => Mage::helper('dls_blog')->__('URL Key'),
            'index' => 'url_key',
                )
        );
        $this->addColumn(
                'action', array(
            'header' => Mage::helper('dls_blog')->__('Action'),
            'width' => '100',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => Mage::helper('dls_blog')->__('Edit'),
                    'url' => array('base' => '*/*/edit'),
                    'field' => 'id'
                )
            ),
            'filter' => false,
            'is_system' => true,
            'sortable' => false,
                )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('dls_blog')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('dls_blog')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('dls_blog')->__('XML'));
        return parent::_prepareColumns();
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('blogset');
        $this->getMassactionBlock()->addItem(
                'delete', array(
            'label' => Mage::helper('dls_blog')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('dls_blog')->__('Are you sure?')
                )
        );
        $this->getMassactionBlock()->addItem(
                'status', array(
            'label' => Mage::helper('dls_blog')->__('Change Status'),
            'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
            'additional' => array(
                'status' => array(
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper('dls_blog')->__('Status'),
                    'values' => array(
                        '1' => Mage::helper('dls_blog')->__('Enabled'),
                        '0' => Mage::helper('dls_blog')->__('Disabled'),
                    )
                )
            )
                )
        );
        $this->getMassactionBlock()->addItem(
                'custom_default_filter', array(
            'label' => Mage::helper('dls_blog')->__('Change Default filter'),
            'url' => $this->getUrl('*/*/massCustomDefaultFilter', array('_current' => true)),
            'additional' => array(
                'flag_custom_default_filter' => array(
                    'name' => 'flag_custom_default_filter',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper('dls_blog')->__('Default Filter'),
                    'values' => Mage::getModel('dls_blog/blogset_attribute_source_customdefaultfilter')
                            ->getAllOptions(true),
                )
            )
                )
        );
        $values = Mage::getResourceModel('dls_blog/layoutdesign_collection')->toOptionHash();
        $values = array_reverse($values, true);
        $values[''] = '';
        $values = array_reverse($values, true);
        $this->getMassactionBlock()->addItem(
                'layoutdesign_id', array(
            'label' => Mage::helper('dls_blog')->__('Change Layout Design'),
            'url' => $this->getUrl('*/*/massLayoutdesignId', array('_current' => true)),
            'additional' => array(
                'flag_layoutdesign_id' => array(
                    'name' => 'flag_layoutdesign_id',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper('dls_blog')->__('Layout Design'),
                    'values' => $values
                )
            )
                )
        );
        return $this;
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    public function getGridUrl() {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

    protected function _afterLoadCollection() {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

}
