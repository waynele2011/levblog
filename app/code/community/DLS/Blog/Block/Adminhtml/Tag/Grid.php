<?php

class DLS_Blog_Block_Adminhtml_Tag_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('tagGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('dls_blog/tag')
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
                'blogset_id', array(
            'header' => Mage::helper('dls_blog')->__('Blog'),
            'index' => 'blogset_id',
            'type' => 'options',
            'options' => Mage::getResourceModel('dls_blog/blogset_collection')
                    ->toOptionHash(),
            'renderer' => 'dls_blog/adminhtml_helper_column_renderer_parent',
            'params' => array(
                'id' => 'getBlogsetId'
            ),
            'base_link' => 'adminhtml/blog_blogset/edit'
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
                'slug', array(
            'header' => Mage::helper('dls_blog')->__('Slug'),
            'index' => 'slug',
            'type' => 'text',
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
        $this->getMassactionBlock()->setFormFieldName('tag');
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
        $values = Mage::getResourceModel('dls_blog/blogset_collection')->toOptionHash();
        $values = array_reverse($values, true);
        $values[''] = '';
        $values = array_reverse($values, true);
        $this->getMassactionBlock()->addItem(
                'blogset_id', array(
            'label' => Mage::helper('dls_blog')->__('Change Blog'),
            'url' => $this->getUrl('*/*/massBlogsetId', array('_current' => true)),
            'additional' => array(
                'flag_blogset_id' => array(
                    'name' => 'flag_blogset_id',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper('dls_blog')->__('Blog'),
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
