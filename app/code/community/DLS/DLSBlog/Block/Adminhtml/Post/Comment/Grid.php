<?php

class DLS_DLSBlog_Block_Adminhtml_Post_Comment_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('postCommentGrid');
        $this->setDefaultSort('ct_comment_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getResourceModel('dls_dlsblog/post_comment_post_collection');
        $collection->addStoreData();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn(
                'ct_comment_id', array(
            'header' => Mage::helper('dls_dlsblog')->__('Id'),
            'index' => 'ct_comment_id',
            'type' => 'number',
            'filter_index' => 'ct.comment_id',
                )
        );
        $this->addColumn(
                'title', array(
            'header' => Mage::helper('dls_dlsblog')->__('Title'),
            'index' => 'title',
            'filter_index' => 'title',
                )
        );
        $this->addColumn(
                'ct_title', array(
            'header' => Mage::helper('dls_dlsblog')->__('Comment Title'),
            'index' => 'ct_title',
            'filter_index' => 'ct.title',
                )
        );
        $this->addColumn(
                'ct_name', array(
            'header' => Mage::helper('dls_dlsblog')->__('Poster name'),
            'index' => 'ct_name',
            'filter_index' => 'ct.name',
                )
        );
        $this->addColumn(
                'ct_email', array(
            'header' => Mage::helper('dls_dlsblog')->__('Poster email'),
            'index' => 'ct_email',
            'filter_index' => 'ct.email',
                )
        );
        $this->addColumn(
                'ct_status', array(
            'header' => Mage::helper('dls_dlsblog')->__('Status'),
            'index' => 'ct_status',
            'filter_index' => 'ct.status',
            'type' => 'options',
            'options' => array(
                DLS_DLSBlog_Model_Post_Comment::STATUS_PENDING =>
                Mage::helper('dls_dlsblog')->__('Pending'),
                DLS_DLSBlog_Model_Post_Comment::STATUS_APPROVED =>
                Mage::helper('dls_dlsblog')->__('Approved'),
                DLS_DLSBlog_Model_Post_Comment::STATUS_REJECTED =>
                Mage::helper('dls_dlsblog')->__('Rejected'),
            )
                )
        );
        $this->addColumn(
                'ct_created_at', array(
            'header' => Mage::helper('dls_dlsblog')->__('Created at'),
            'index' => 'ct_created_at',
            'width' => '120px',
            'type' => 'datetime',
            'filter_index' => 'ct.created_at',
                )
        );
        $this->addColumn(
                'ct_updated_at', array(
            'header' => Mage::helper('dls_dlsblog')->__('Updated at'),
            'index' => 'ct_updated_at',
            'width' => '120px',
            'type' => 'datetime',
            'filter_index' => 'ct.updated_at',
                )
        );
        if (!Mage::app()->isSingleStoreMode() && !$this->_isExport) {
            $this->addColumn(
                    'stores', array(
                'header' => Mage::helper('dls_dlsblog')->__('Store Views'),
                'index' => 'stores',
                'type' => 'store',
                'store_all' => true,
                'store_view' => true,
                'sortable' => false,
                'filter_condition_callback' => array($this, '_filterStoreCondition'),
                    )
            );
        }
        $this->addColumn(
                'action', array(
            'header' => Mage::helper('dls_dlsblog')->__('Action'),
            'width' => '100',
            'type' => 'action',
            'getter' => 'getCtCommentId',
            'actions' => array(
                array(
                    'caption' => Mage::helper('dls_dlsblog')->__('Edit'),
                    'url' => array('base' => '*/*/edit'),
                    'field' => 'id'
                )
            ),
            'filter' => false,
            'is_system' => true,
            'sortable' => false,
                )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('dls_dlsblog')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('dls_dlsblog')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('dls_dlsblog')->__('XML'));
        return parent::_prepareColumns();
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('ct_comment_id');
        $this->setMassactionIdFilter('ct.comment_id');
        $this->setMassactionIdFieldOnlyIndexValue(true);
        $this->getMassactionBlock()->setFormFieldName('comment');
        $this->getMassactionBlock()->addItem(
                'delete', array(
            'label' => Mage::helper('dls_dlsblog')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('dls_dlsblog')->__('Are you sure?')
                )
        );
        $this->getMassactionBlock()->addItem(
                'status', array(
            'label' => Mage::helper('dls_dlsblog')->__('Change status'),
            'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
            'additional' => array(
                'status' => array(
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper('dls_dlsblog')->__('Status'),
                    'values' => array(
                        DLS_DLSBlog_Model_Post_Comment::STATUS_PENDING =>
                        Mage::helper('dls_dlsblog')->__('Pending'),
                        DLS_DLSBlog_Model_Post_Comment::STATUS_APPROVED =>
                        Mage::helper('dls_dlsblog')->__('Approved'),
                        DLS_DLSBlog_Model_Post_Comment::STATUS_REJECTED =>
                        Mage::helper('dls_dlsblog')->__('Rejected'),
                    )
                )
            )
                )
        );
        return $this;
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getCtCommentId()));
    }

    public function getGridUrl() {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

    protected function _filterStoreCondition($collection, $column) {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $collection->setStoreFilter($value);
        return $this;
    }

}
